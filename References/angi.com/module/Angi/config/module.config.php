<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Angi\Controller\Angi' => 'Angi\Controller\AngiController',
        ),
    ),

    'router' => array(
        'routes' => array (
            'angi' => array (
                'type' => 'segment',
                'options' => array(
                    'route' => '/angi[/:action][/:id]',
                    'constraints' => array (
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array (
                        'controller' => 'Angi\Controller\Angi',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'angi' => __DIR__ . '/../view',
        ),
    )
);