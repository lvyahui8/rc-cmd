<?php
/**
 * Created by PhpStorm.
 * User: lvyahui
 * Date: 2017/11/28
 * Time: 23:40
 */

namespace Ruochen\Foundation;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Dotenv\Dotenv;
use Exception;
use GetOpt\ArgumentException;
use GetOpt\ArgumentException\Missing;
use GetOpt\Command;
use GetOpt\GetOpt;
use GetOpt\Operand;
use GetOpt\Option;
use JBZoo\Utils\FS;
use Monolog\Logger;
use ReflectionClass;
use Ruochen\Annotations\Desc;
use Ruochen\Logs\ToolLogger;
use Ruochen\Annotations\DefaultCommand;

abstract class CommandTool extends GetOpt
{
    use Singleton;
    /**
     * @var Logger
     */
    protected $logger;

    protected $toolfile;

    protected $classRf;

    /**
     * @var AnnotationReader
     */
    protected $annoReader;

    /**
     * @var \ReflectionMethod[]
     */
    protected $commandMethodMap = [];

    private $defaultCommand;

    protected $classname;

    /**
     * CommandTool constructor.
     * @param string $toolfile
     * @param array|string $options
     * @param array $settings
     */
    public function __construct($toolfile = null, $options=null, $settings = [])
    {
        parent::__construct($options,$settings);
        $this->classname = get_class($this);
        $this->classRf = new ReflectionClass($this->classname);
        $this->toolfile = $toolfile == null ? FS::filename($this->classRf->getFileName()) : $toolfile;
        $this->init();
    }

    private function init()
    {
        $this->loadEnv();
        $this->initLogger();
        $this->initAnnoReader();
        $this->bindOptions();
    }

    private function initLogger()
    {
        $this->logger = ToolLogger::getInstance(FS::filename($this->toolfile));
    }

    private function bindOptions()
    {

        $this->addOptions([

            Option::create('v', 'version', GetOpt::NO_ARGUMENT)
                ->setDescription('Show version information and quit'),

            Option::create('h', 'help', GetOpt::NO_ARGUMENT)
                ->setDescription('Show this help and quit'),

        ]);


        $ref = new \ReflectionClass(get_class($this));
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        $opreadMap = [
            'required'  =>  Operand::REQUIRED,
            'multiple'  =>  Operand::MULTIPLE,
            'optional'  =>  Operand::OPTIONAL,
        ];
        $optionMap = [
            'noArg'       => GetOpt::NO_ARGUMENT,
            'requiredArg' => GetOpt::REQUIRED_ARGUMENT,
            'optionalArg' => GetOpt::OPTIONAL_ARGUMENT,
            'multipleArg' => GetOpt::MULTIPLE_ARGUMENT,
        ];
        $classOptionAnno = $this->annoReader->getClassAnnotation($ref,\Ruochen\Annotations\Option::class);

        if($classOptionAnno != null){
            $this->addOption(Option::create($classOptionAnno->short,
                $classOptionAnno->long,isset($classOptionAnno->mode) ? $optionMap[$classOptionAnno->mode] : GetOpt::NO_ARGUMENT)
                ->setDescription($classOptionAnno->desc));
        }

        foreach($methods as $method){
            $commandAnno = $this->annoReader
                ->getMethodAnnotation($method,\Ruochen\Annotations\Command::class);

            if($commandAnno != null){
                $commandName = isset($commandAnno->name) ? $commandAnno->name : $method->getName();
                $command = Command::create($commandName,[$this,$method->getName()]);

                $opreadAnno = $this->annoReader->getMethodAnnotation($method,\Ruochen\Annotations\Operand::class);
                if($opreadAnno && isset($opreadAnno->name)){
                    $opread = Operand::create($opreadAnno->name
                        ,isset($opreadMap[$opreadAnno->mode]) ? $opreadMap[$opreadAnno->mode] : Operand::REQUIRED);

                    $command->addOperand($opread);
                }

                if($commandAnno->desc != null){
                    $command->setDescription($commandAnno->desc);
                } else {
                    $descAnno = $this->annoReader->getMethodAnnotation($method,Desc::class);
                    if($descAnno){
                        $command->setDescription($descAnno->value);
                    }
                }
                $optionAnno = $this->annoReader->getMethodAnnotation($method,\Ruochen\Annotations\Option::class);
                if($optionAnno != null) {
                    $command->addOption(Option::create($optionAnno->short,$optionAnno->long,
                        isset($optionAnno->mode) ? $optionMap[$optionAnno->mode] : GetOpt::NO_ARGUMENT)->setDescription($optionAnno->desc));
                }
                $this->commandMethodMap[$method->getName()] = $method;
                $this->addCommand($command);
                if($this->annoReader->getMethodAnnotation($method,DefaultCommand::class)){
                    $this->setDefaultCommand($commandName);
                }
            }
        }
    }

    private function initAnnoReader()
    {
        $docPath = realpath(__DIR__.'/../../../src');
        AnnotationRegistry::registerAutoloadNamespace('Ruochen\Annotations',$docPath);
        $this->annoReader = new AnnotationReader();
    }

    public function process($arguments = null)
    {
        try{
            try {
                parent::process($arguments);
            } catch (Missing $exception) {
                if (!$this->getOption('help')) {
                    throw $exception;
                }
            }
        } catch(ArgumentException $e){
            file_put_contents('php://stderr', $e->getMessage() . PHP_EOL);
            echo PHP_EOL . $this->getHelpText();
            exit;
        }

        if ($this->getOption('version')) {
            echo sprintf('%s: %s' . PHP_EOL, FS::filename($this->toolfile)
                ,defined('VERSION') ? VERSION : '1.0.0' );
            exit;
        }
        $command = $this->getCommand();
        if (!$command) {
            $exec = false;
            if($this->getDefaultCommand() != null){
                $command = $this->getCommand($this->getDefaultCommand());
                if($command){
                    $exec = true;
                }
            }
            if(! $exec || $this->getOption('help')){
                // 这个方法可能会卡死
                echo $this->getHelpText();
                exit;
            }
        }
        $handler = $command->getHandler();
        return  call_user_func($handler);
    }

    private function loadEnv()
    {
//        $dotenv = new D(__DIR__);
//        $dotenv->load();
        $dotenv = new Dotenv(base_path());
        $dotenv->load();
    }

    /**
     * @return mixed
     */
    public function getDefaultCommand()
    {
        return $this->defaultCommand;
    }

    /**
     * @param mixed $defaultCommand
     */
    public function setDefaultCommand($defaultCommand)
    {
        $this->defaultCommand = $defaultCommand;
    }
}