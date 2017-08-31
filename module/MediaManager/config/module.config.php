<?php
return array(
	'service_manager' => array(
		'invokables' => array(
			'MediaManagerListener' => 'MediaManager\Event\MediaManagerListener',
		),
	),
	'images' => array(
		'defaultSizes' => array(
			'Thumb' => array(
				'type' => 'crop',
				'width' => 90,
				'height' => 90,
				'folder' => '__thumbs_90_90_crop',
			),
			'Small' => array(
				'type' => 'scale',
				'width' => 80,
				'height' => 80,
				'folder' => '__thumbs_80_80_scale',
			),
			'Medium' => array(
				'type' => 'scale',
				'width' => 200,
				'height' => 200,
				'folder' => '__thumbs_200_200_scale',
			),
			'Large' => array(
				'type' => 'scale',
				'width' => 600,
				'height' => 500,
				'folder' => '__thumbs_600_500_scale'
			),
			'CmlpGroup' => array(
				'type' => 'crop',
				'width' => 237,
				'height' => 143,
				'folder' => '__thumbs_cmlp_group',
			),
			'Accomodations' => array(
				'type' => 'scale',
				'width' => 460,
				'height' => 260,
				'folder' => '__thumbs_460_260_crop',
			),
			'Default' => array(
				'type' => 'crop',
				'width' => 459,
				'height' => 262,
				'folder' => '__thumbs_default',
			),
		),
		'mediaManagerSizes' => array(

//			'MainVisualHome' => array(
//					'type' => 'crop',
//					'width' => 980,
//					'height' => 450,
//					'folder' => '__thumbs_980_450_crop',
//				),
//			'MainVisualSubpage' => array(
//					'type' => 'crop',
//					'width' => 980,
//					'height' => 250,
//					'folder' => '__thumbs_980_250_crop',
//				),
//			'Accomodations' => array(
//					'type' => 'scale',
//					'width' => 460,
//					'height' => 260,
//					'folder' => '__thumbs_460_260_crop',
//				),
			'Default' => array(
					'type' => 'crop',
					'width' => 459,
					'height' => 262,
					'folder' => '__thumbs_default',
				),
			'ExecutiveTeam' => array(
					'type' => 'crop',
					'width' => 233,
					'height' => 303,
					'folder' => '__thumbs_executive_team',
				),
//			'VideoThumbs' => array(
//					'type' => 'crop',
//					'width' => 306,
//					'height' => 175,
//					'folder' => '__thumbs_video_thumbs',
//				),
			'PhotoThumbs' => array(
					'type' => 'crop',
					'width' => 177,
					'height' => 120,
					'folder' => '__thumbs_photo_thumbs',
				),
			'HotelCardThumbs' => array(
					'type' => 'crop',
					'width' => 307,
					'height' => 129,
					'folder' => '__thumbs_hotel_card_thumbs',
				),
			'MarkerInfo' => array(
					'type' => 'crop',
					'width' => 179,
					'height' => 140,
					'folder' => '__thumbs_marker_info',
				),
			'Submedium' => array(
					'type' => 'crop',
					'width' => 308,
					'height' => 202,
					'folder' => '__thumbs_submedium',
				),
			'Subsmall' => array(
					'type' => 'crop',
					'width' => 181,
					'height' => 123,
					'folder' => '__thumbs_subsmall',
				),
			'FeaturedSpecialsListView' => array(
					'type' => 'crop',
					'width' => 258,
					'height' => 201,
					'folder' => '__thumbs_featured_specials_list_view',
				),
			'FeaturedSpecialsMini' => array(
					'type' => 'crop',
					'width' => 124,
					'height' => 104,
					'folder' => '__thumbs_featured_specials_mini',
				),
			'FeaturedSpecialsLarge' => array(
					'type' => 'crop',
					'width' => 592,
					'height' => 442,
					'folder' => '__thumbs_featured_specials_large',
				),
			'FeaturedSpecials' => array(
					'type' => 'crop',
					'width' => 264,
					'height' => 160,
					'folder' => '__thumbs_featured_specials',
				),
			'FeaturedDestinations' => array(
					'type' => 'crop',
					'height' => 394,
					'folder' => '__thumbs_featured_destinations',
				),
			'HeroBrand' => array(
					'type' => 'crop',
					'width' => 1400,
					'height' => 604,
					'folder' => '__thumbs_hero_brand',
				),
			'HeroProperty' => array(
					'type' => 'crop',
					'width' => 1400,
					'height' => 659,
					'folder' => '__thumbs_hero_property',
				),
			'Hero' => array(
					'type' => 'crop',
					'width' => 1400,
					'height' => 509,
					'folder' => '__thumbs_hero',
				),
			'phoenixRooms' => array(
					'type' => 'crop',
					'width' => 459,
					'height' => 302,
					'folder' => '__thumbs_phoenixRooms',
				),
			'featuredImage' => array(
					'type' => 'crop',
					'width' => 953,
					'height' => 563,
					'folder' => '__thumbs_featuredImage',
				),
			'confirmationPage' => array(
					'type' => 'crop',
					'width' => 662,
					'height' => 292,
					'folder' => '__thumbs_confirmation_page',
				),
		),
	),
	'view_helpers' => array(
		'invokables' => array(
			'mediaAttachmentListItem' => 'MediaManager\Helper\MediaAttachmentListItem',
			'form_mediaAttachments'   => 'MediaManager\Form\View\Helper\MediaAttachments',
			'imageElement'            => 'MediaManager\Helper\ImageElement',
			'getImageElement'         => 'MediaManager\Helper\GetImageElement',
			'isReadOnly'              => 'MediaManager\Helper\IsReadOnly',
			'cropPresetHelper'        => 'MediaManager\Helper\CropPresets',
			'cropRatioHelper'         => 'MediaManager\Helper\CropRatios',
		)
	),
	'form_elements' => array(
		'invokables' => array(
			'MediaAttachments' => 'MediaManager\Form\Element\MediaAttachments'
		)
	),
	'router' => array(
		'routes' => array(
			'mediaManager-direct' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/mediaManager[-:langCode][_:controller][/:action[/:itemId]]',
					'constraints' => array(
						'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
						'itemId'     => '[0-9][0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'MediaManager\Controller',
						'controller' => 'Index',
						'action' => 'index',
						'keywords' => '',
						'langCode' => '',
					),
				),
			),
			'mediaManager-sockets' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/sockets/mediaManager[/:action]', //[/:action[/:itemId]]',
					'constraints' => array(
						'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
						'itemId'     => '[0-9][0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'MediaManager\Controller',
						'controller' => 'Sockets',
						'action' => 'index',
						'keywords' => '',
						'langCode' => '',
						'subsite' => ''
					),
				),
			),
			'mediaManager-toolbox' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/toolbox/tools/mediaManager[-:langCode][_:controller][/:action[/:itemId]]',
					'defaults' => array(
						'__NAMESPACE__' => 'MediaManager\Controller',
						'controller' => 'Toolbox',
						'action' => 'index',
						'keywords' => '',
						'langCode' => '',
						'subsite' => ''
						),
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9]*',
						'controller' => '[a-zA-Z][a-zA-Z0-9]*',
						'langCode' => '[a-z][a-z]*',
						'itemId'     => '[0-9][0-9]*',
					)
				),
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'MediaManager\Controller\Index' => 'MediaManager\Controller\IndexController',
			'MediaManager\Controller\Toolbox' => 'MediaManager\Controller\ToolboxController',
			'MediaManager\Controller\Sockets' => 'MediaManager\Controller\SocketsController'
		),
	),
	'view_manager' => array(
		'template_map' => array(
			'imageswitch-element'     => __DIR__ . '/../view/media-manager/helpers/media-image-element.phtml',
		 ),
		'template_path_stack' => array(
			__DIR__ . '/../view'
		),
	),
	'doctrine' => array(
		'driver' => array(
			//Configure the mapping driver for entities in Application module
			'app_entities' => array(
				'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(realpath(__DIR__ . '/../src/MediaManager/Entity'))
			),
			'admin_entities' => array(
				'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(realpath(__DIR__ . '/../src/MediaManager/Entity/Admin'))
			),
			//Add configured driver to orm_default entity manager
			'orm_default' => array(
				'drivers' => array(
					 'MediaManager\Entity' => 'app_entities'
				)
			),
			'orm_admin' => array(
				'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
				'drivers' => array(
					'MediaManager\Entity\Admin' => 'admin_entities'
				)
			),
		),
	),
	'phoenix-filters' => array(
		'image' => 'phoenix-filters-image',
	),
	'mediamanager-crop-sizes' => array(
		'presets' => array(
			'Example 1' => array(
				'width' => '100',
				'height' => '200',
			),
			'Example 2' => array(
				'width' => '80',
				'height' => '80',
			),
			'Example 3' => array(
				'width' => '200',
				'height' => '100',
			),
		),
		'ratios' => array(
			'3:2' => '1.5',
			'4:3' => '1.33',
			'1:1' => '1',
			'3:4' => '0.75',
			'2:3' => '0.67',
		),
	),
	'mediamanager-allowed-image-types' => array(
		'jpeg' => 'image/jpeg',
		'gif'  => 'image/gif',
		'png'  => 'image/png',
		'jpg'  => 'image/jpg',
//		'bmp'  => 'image/bmp',
//		'tif'  => 'image/tif',
//		'tiff' => 'image/tiff'
	),
	'mediamanager-allowed-doc-types' => array(
		'csv'  => 'text/csv',
		'doc'  => 'application/vnd.openxmlformats-officedocument.wordpressingml.document',
		'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',		
		'pdf'  => 'application/pdf',
		'ppt'  => 'application/vnd.ms-powerpoint',
		'rtf'  => 'application/rtf',
		'txt'  => 'text/plain',
		'xls'  => 'application/vnd.ms-excel',
		'zip'  => 'application/zip',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	),
);