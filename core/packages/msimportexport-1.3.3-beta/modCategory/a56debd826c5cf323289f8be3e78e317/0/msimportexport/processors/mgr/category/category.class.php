<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';

class msImportExportCategoryProcessor extends modObjectCreateProcessor {
	/** @var Msie $msie */
	private $msie;
	public $checkListPermission = true;
	/** {@inheritDoc} */
	public function initialize() {
		$this->msie = new Msie($this->modx);
		return true;
	}


	/** {@inheritDoc} */
	public function process() {
		$categories = array_map('trim', explode(',', $this->modx->getOption('msimportexport.export.parents', null, '')));
		$cid = $this->getProperty('category_id');
		if ($cid > 0) {
			$key = array_search($cid, $categories);
			if($key !== false) {
				unset($categories[$key]);
			} else {
				$categories[] = $cid;
			}
			$categories = array_diff($categories, array(''));
			$this->msie->setOption('msimportexport.export.parents',implode(',', $categories));
		}
		return $this->success('');
	}

}

return 'msImportExportCategoryProcessor';