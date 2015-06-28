<?php

namespace Blog;

use Blog\Model\PostMapper;

class Module
{
    

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}


	public function getAutoloaderConfig()
	{
		return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__, //tương đương với 'Blog' => Blog/src/Blog
                ),
            ),
        );
	}

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'PostMapper' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $mapper = new PostMapper($dbAdapter);
                    return $mapper;
                }
            )
        );
    }

}