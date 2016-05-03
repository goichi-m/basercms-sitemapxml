<?php
/**
 * イベントリスナー
 */
class SitemapxmlControllerEventListener extends BcControllerEventListener {

  /**
   * 登録イベント
   */
  public $events = array(
    'Pages.afterAdd',
    'Pages.afterEdit',
    'Blog.BlogPosts.afterAdd',
    'Blog.BlogPosts.afterEdit',
  );

  /**
   * pagesAfterAdd
   *
   * @return void
   * @access public
   */
  public function pagesAfterAdd(CakeEvent $event) {
    //書き出し先のパス取得
    $path = WWW_ROOT . Configure::read('Sitemapxml.filename');
    //書き出し実行
    $sitemap = $this->requestAction('/admin/sitemapxml/sitemapxml/create', array('return', $event));
    ClassRegistry::removeObject('View');
    $File = new File($path);
    $File->write($sitemap);
    $File->close();
    chmod($path, 0666);
  }

  /**
   * pagesAfterEdit
   *
   * @return void
   * @access public
   */
  public function pagesAfterEdit(CakeEvent $event) {
    $this->pagesAfterAdd($event);
  }

  /**
   * blogBlogPostsAfterAdd
   *
   * @return void
   * @access public
   */
  public function blogBlogPostsAfterAdd(CakeEvent $event) {
    $this->pagesAfterAdd($event);
  }

  /**
   * blogBlogPostsAfterEdit
   *
   * @return void
   * @access public
   */
  public function blogBlogPostsAfterEdit(CakeEvent $event) {
    $this->pagesAfterAdd($event);
  }
}