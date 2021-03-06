<?php namespace module\template\blockSlideTop;
//Class Block extends BlockGlobal{
Class Block extends \BlockGlobal {
	/**
	 * $returnData - bien chua data de xuat ra o giao dien
	 *@return array
	 */
	public $returnData = array();
	public $lang;
	public $idw;
	public $limit = 4;
	public $mod   = 'template';
	public function __construct() {
		global $_B, $web;
		$this->lang = $_B['lang'];
		$this->idw  = $web['idw'];
		db_connect($this->mod);
		$this->setData();

	}

	/**
	 *funntion setData;
	 *gan du lieu cho returnData
	 *@param
	 *@return void
	 */
	private function setData() {
		$data = $this->getSlide();
		//$data = $this->ModifySlide($data);
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		$this->returnData = $data;

	}
	private function ModifySlide($data) {
		$result = array();
		foreach ($data as $slide) {
			$images   = $this->getImageBySlide($slide['id']);
			$result[] = array(
				'id'          => $slide['id'],
				'title'       => $slide['title'],
				'description' => $slide['description'],
				'position'    => $slide['position'],
				'meta_title'  => $slide['meta'],
				'image_slide' => $images,
			);
		}
		return $result;
	}

	/**
	 *funntion getData;
	 *gan du lieu cho returnData
	 *@param
	 *@return void
	 */
	private function getSlide() {
		$slideObj = new \Model($this->lang . '_slide');
		$slideObj->where('idw', $this->idw);
		$slideObj->where('status', 1);
		$slideObj->where('position', 4);
		$slideObj->orderBy('sort', 'ASC');
		$results = $slideObj->get(null, $this->limit, '*');
		return $results;
	}

	//Hàm lấy ảnh trong slide
	private function getImageBySlide($id) {
		$slideImageObj = new \Model($this->lang . '_slide_image');
		$slideImageObj->where('idw', $this->idw);
		$slideImageObj->where('status', 1);
		$slideImageObj->where('slide_id', $id);
		$slideImageObj->orderBy('sort', 'ASC');
		$results = $slideImageObj->get(null, null, '*');
		$data    = array();
		foreach ($results as $key => $value) {
			$image  = loadImage($value['src_link'], 'resize', 300, 250);
			$data[] = array(
				'id'          => $value['id'],
				'idw'         => $value['idw'],
				'id_lang'     => $value['id_lang'],
				'thumb'       => $image,
				'title'       => $value['title'],
				'description' => $value['description'],
				'width'       => $value['width'],
				'height'      => $value['height'],
				'link'        => $value['link'],
			);
		}
		return $data;
	}
}