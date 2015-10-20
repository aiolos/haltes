<?php
/**
 * Haltes
 *
 * PHP Version 5.5
 *
 * @link      http://www.mobiliteitsfabriek.nl
 * @copyright Copyright (c) 2015 Mobiliteitsfabriek
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Index' => '\Application\Controller\IndexController',
            'Application\Busstop' => '\Application\Controller\BusstopController',
            'Application\Setup' => '\Application\Controller\SetupController',
            'Application\Map' => '\Application\Controller\MapController',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
        )
    ),
    'input_filters' => array(
        'invokables' => array(
         ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\map',
                        'action'     => 'index',
                    ),
                ),
            ),
            'error' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/error/:action',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Error',
                        'action' => 'error'
                    ),
                ),
            ),
            'busstop' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/busstops/[:code]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Busstop',
                        'action'     => 'view',
                        'code' => '',
                    ),
                ),
            ),
            'busstops' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/busstops[/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Busstop',
                        'action'     => 'index',
                    ),
                ),
            ),
            'distance' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/distance/:codeFrom/:codeTo',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Busstop',
                        'action'     => 'distance',
                    ),
                ),
            ),
            'path' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/path/:from/:to',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Busstop',
                        'action'     => 'path',
                    ),
                ),
            ),
            'map' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/map[/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Map',
                        'action'     => 'index',
                    ),
                ),
            ),
            'description' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/description/:description',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Busstop',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'setup' => array(
                    'options' => array(
                        'route' => 'setup',
                        'defaults' => array(
                            'controller' => 'Application\Setup',
                            'action'     => 'index',
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'form_elements' => array(
       'invokables' => array(
       ),
       'factories' => array(
           'PathForm' => function ($sm) {
               $form = new \Application\Form\PathForm();
               $form->setServiceLocator($sm);
               $form->init();
               return $form;
           },
           'SearchForm' => function ($sm) {
               $form = new \Application\Form\SearchForm();
               $form->setServiceLocator($sm);
               $form->init();

               return $form;
           },
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/Application/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'zfcuser_entity',
                ),
            ),
        ),
    ),
);
