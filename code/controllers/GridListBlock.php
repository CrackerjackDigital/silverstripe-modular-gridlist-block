<?php
namespace Modular\Controllers;

use Modular\Application;
use Modular\Relationships\HasBlocks;

/**
 * Standalone controller which services requests to e.g. '/gridlist/items?path=...&filter=...&start=...&limit=...'
 *
 * @package Modular\GridList\Controllers
 */
class GridListBlock extends \ContentController {
	private static $url_handlers = [
		'items' => 'items',
	];
	private static $allowed_actions = [
		'items' => true
	];

	public static function class_name() {
		return get_called_class();
	}

	/**
	 * TODO: this should be handed the ID of a GridListBlock not the page to get the items for that BLock incase
	 * there are more than one GridListBlock on the page.
	 *
	 * @param \SS_HTTPRequest $request
	 * @return mixed
	 */
	public function items(\SS_HTTPRequest $request) {
		$page = null;

		/** @var \Page|HasBlocks $page */
		if ($pageID = $request->param('PageID')) {
			$page = \Page::get()->byID($pageID);
		} else {
			if ($path = Application::path_for_request($request)) {
				$page = Application::page_for_path($path);
			}
		}
		if ($page) {
			if ($page->hasExtension(\Modular\Relationships\HasBlocks::class_name())) {
				\Director::set_current_page($page);

				/** @var GridListBlock $gridList */
				if ($gridListBlock = $page->Blocks()->find('ClassName', GridListBLock::class_name())) {
					return $gridListBlock->renderWith("GridListItems");
				}
			}
		}
	}

}