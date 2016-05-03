<?php
/*
* Sitemapxml plugin
*/

/**
 * includes
 */
App::uses('BcPluginAppModel', 'Model');

/**
 * モデル
 */
class Sitemapxml extends BcPluginAppModel
{

  /**
   * クラス名
   *
   * @var string
   * @access public
   */
  public $name = 'Sitemapxml';

  /**
   * プラグイン名
   *
   * @var string
   * @access public
   */
  public $plugin = 'Sitemapxml';

  /**
   * データベースの利用
   *
   * @var boolean
   * @access public
   */
  public $useTable = false;

  /**
   * コンテンツ（ページとブログ）のデータを返す。
   *
   * @return array()
   * @access public
   */
  public function getContentData()
  {
    //モデル利用
    $contentModel = ClassRegistry::init('Content');
    //データの取得
    $data = array(); //整形データ格納用
    $i = 0; //配列カウント用
    $contentDatas = $contentModel->find('all', array(
      'conditions' => array(
        'Content.status' => true
      )));
    //データ
    if (!empty($contentDatas)) {
      foreach ($contentDatas as $contentData) {
        $data[$i]['Content']['priority'] = $contentData['Content']['priority'];
        $data[$i]['Content']['modified'] = $contentData['Content']['modified'];
        $data[$i]['Content']['created'] = $contentData['Content']['created'];
        $data[$i]['Content']['url'] = $contentData['Content']['url'];
        $i++;
      }
    }
    //配列をcreatedで並び替える。
    $result = Hash::sort($data, '{n}.Content.created', 'desc');
    return $result;
  }
}
