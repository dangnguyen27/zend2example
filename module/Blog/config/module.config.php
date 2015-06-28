<?php 
/**
* Cấu hình routes, các controller thực hiện 
*/

namespace Blog;

return array(

	'controllers' => array(
		'invokables'=>array(
			'Blog\Controller\Post' => 'Blog\Controller\PostController', 
		)
	),

	'router' => array( 
		'routes' => array(

			'post' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/post[/:action][/:id]' ,//
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        
					),
					'defaults' => array(
						'controller' => 'Blog\Controller\Post',
						'action' => 'index'
					)
				)
			)
		) 
	),

	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__ .'/../view',
		)
	),

);