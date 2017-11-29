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
use Exception;
use GetOpt\ArgumentException;
use GetOpt\ArgumentException\Missing;
use GetOpt\Command;
use GetOpt\GetOpt;
use GetOpt\Operand;
use GetOpt\Option;
use JBZoo\Utils\FS;
use Monolog\Logger;

abstract class CommandTool extends GetOpt
{
    /**
     * @var Logger
     */
    protected $logger;

    protected $toolfile;

    /**
     * @var AnnotationReader
     */
    protected $annoReader;

    /**
     * @var \ReflectionMethod[]
     */
    protected $commandMethodMap = [];

    /**
     * CommandTool constructor.
     * @param string $toolfile
     * @param array|string $options
     * @param array $settings
     */
    public function __construct($toolfile, $options=null, $settings = [])
    {
        parent::__construct($options,$settings);
        $this->toolfile = $toolfile;
        $this->init();
    }

    private function init()
    {
        $this->initLogger();
        $this->initAnnoReader();
        $this->bindOptions();
    }

    private function initLogger()
    {
        $this->logger = new Logger((FS::filename($this->toolfile)));
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
        foreach($methods as $method){
            $commandAnno = $this->annoReader
                ->getMethodAnnotation($method,'\Ruochen\Annotations\Command');

            if($commandAnno != null){
                $commandName = $commandAnno->name ? $commandAnno->name : $method->getName();
                $command = Command::create($commandName,[$this,$method->getName()]);

                $opreadAnno = $this->annoReader->getMethodAnnotation($method,\Ruochen\Annotations\Opread::class);

                if($opreadAnno){
                    $opread = Operand::create($opreadAnno->name
                        ,isset($opreadMap[$opreadAnno->mode]) ? $opreadMap[$opreadAnno->mode] : Operand::REQUIRED);

                    $command->addOperand($opread);
                }

                $descAnno = $this->annoReader->getMethodAnnotation($method,\Ruochen\Annotations\Desc::class);
                if($descAnno){
                    $command->setDescription($descAnno->value);
                }
                $this->commandMethodMap[$method->getName()] = $method;
                $this->addCommand($command);
            }
        }
    }

    private function initAnnoReader()
    {
        AnnotationRegistry::registerAutoloadNamespace('Ruochen\Annotations',base_path().'/src');
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
        if (!$command || $this->getOption('help')) {
            echo $this->getHelpText();
            exit;
        }
        $handler = $command->getHandler();
        return  call_user_func($handler);
    }
}