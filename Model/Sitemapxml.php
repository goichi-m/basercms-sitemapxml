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
    $blogModel = ClassRegistry::init('Blog.BlogPost');
    //データの取得
    $data = array(); //整形データ格納用
    $i = 0; //配列カウント用
    $contentDatas = $contentModel->find('all', array(
      'conditions' => array(
        'Content.status' => true
      )));
    $blogDatas = $blogModel->find('all', array(
      'conditions' => array(
        'BlogPost.status' => true
      )));
    //ブログデータの整形
    if (!empty($blogDatas)) {
      foreach ($blogDatas as $blogData) {
        $data[$i]['Content']['priority'] = 0.1; //書かれた記事の更新頻度
        $data[$i]['Content']['modified'] = $blogData['BlogPost']['modified'];
        $data[$i]['Content']['created'] = $blogData['BlogPost']['created'];
        $data[$i]['Content']['url'] = '/'.$blogData['BlogContent']['name'].'/archives/'.$blogData['BlogPost']['no'];
        $i++;
      }
    }
    //固定ページデータ
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
