// 导航栏配置文件
var outlookbar=new outlook();
var t;
t=outlookbar.addtitle('基本设置','管理首页',1)
outlookbar.additem('信息设置',t,'systemconfig.php?type=basic')
outlookbar.additem('站点SEO',t,'systemconfig.php?type=seo')
outlookbar.additem('参数设置',t,'systemconfig.php?type=arg')
outlookbar.additem('清空缓存',t,'systemconfig.php?type=clear')

t=outlookbar.addtitle('数据库设置','系统设置',1)
outlookbar.additem('备份数据库',t,'backdb.php?type=backdb')
outlookbar.additem('备份数据库(测试)',t,'backdb.php?type=backdb_test')
outlookbar.additem('还原数据库',t,'backdb.php?type=restoredb')
outlookbar.additem('数据表优化',t,'backdb.php?type=youhua')

t=outlookbar.addtitle('网站公告','管理首页',1)
outlookbar.additem('公告列表',t,'con_notice.php?type=newlist')
outlookbar.additem('添加公告',t,'con_notice.php?type=newadd')

t=outlookbar.addtitle('静态化设置','系统设置',1)
outlookbar.additem('生成首页静态',t,'markhtml.php?type=index')
outlookbar.additem('生成分类静态',t,'markhtml.php?type=category')
outlookbar.additem('生成内容静态',t,'markhtml.php?type=article')
outlookbar.additem('生成所有导航',t,'markhtml.php?type=nav')
outlookbar.additem('一键全站生成',t,'markhtml.php?type=all')

//download by http://www.codefans.net
/*t=outlookbar.addtitle('数据统计','系统设置',1)
outlookbar.additem('流量分析',t,'')
outlookbar.additem('搜索引擎',t,'')*/


t=outlookbar.addtitle('管理员设置','系统设置',1)
outlookbar.additem('管理员列表',t,'manager.php?type=list')
outlookbar.additem('添加管理员',t,'manager.php?type=add')
outlookbar.additem('管理员日记',t,'manager.php?type=loglist')
outlookbar.additem('修改密码',t,'manager.php?type=edit')
outlookbar.additem('权限组列表',t,'manager.php?type=group')
outlookbar.additem('添加权限组',t,'manager.php?type=group&tt=add')

t=outlookbar.addtitle('会员设置','会员管理',1)
outlookbar.additem('会员列表',t,'user.php?type=list')
outlookbar.additem('添加会员',t,'user.php?type=info')



t=outlookbar.addtitle('新闻资讯','内容管理',1)
outlookbar.additem('分类列表',t,'con_new.php?type=catelist')
outlookbar.additem('添加分类',t,'con_new.php?type=cateadd')
outlookbar.additem('新闻列表',t,'con_new.php?type=newlist')
outlookbar.additem('添加内容',t,'con_new.php?type=newadd')



t=outlookbar.addtitle('模板案例','内容管理',1)
outlookbar.additem('分类列表',t,'con_case.php?type=catelist')
outlookbar.additem('添加分类',t,'con_case.php?type=cateadd')
outlookbar.additem('案例列表',t,'con_case.php?type=newlist')
outlookbar.additem('添加内容',t,'con_case.php?type=newadd')
outlookbar.additem('颜色分类',t,'con_case.php?type=colorlist')
outlookbar.additem('添加颜色',t,'con_case.php?type=colorinfo')

t=outlookbar.addtitle('网站建设','内容管理',1)
outlookbar.additem('分类列表',t,'con_website.php?type=catelist')
outlookbar.additem('添加分类',t,'con_website.php?type=cateadd')
outlookbar.additem('内容列表',t,'con_website.php?type=newlist')
outlookbar.additem('添加内容',t,'con_website.php?type=newadd')

/*t=outlookbar.addtitle('网站设计','内容管理',1)
outlookbar.additem('内容列表',t,'')
outlookbar.additem('添加内容',t,'')

t=outlookbar.addtitle('建站套餐','内容管理',1)
outlookbar.additem('套餐列表',t,'')
outlookbar.additem('添加套餐',t,'')*/

t=outlookbar.addtitle('客户列表','内容管理',1)
outlookbar.additem('客户分类',t,'con_clientlist.php?type=catelist')
outlookbar.additem('添加分类',t,'con_clientlist.php?type=cateadd')
outlookbar.additem('客户列表',t,'con_clientlist.php?type=newlist')
outlookbar.additem('添加客户',t,'con_clientlist.php?type=newadd')

/*t=outlookbar.addtitle('分类优化','SEO优化',1)
outlookbar.additem('新闻资讯',t,'seo_youhua.php?type=cate_xwzx')
outlookbar.additem('模板案例',t,'seo_youhua.php?type=cate_mbal')
outlookbar.additem('网站建设',t,'seo_youhua.php?type=cate_wzjs')
outlookbar.additem('客户列表',t,'seo_youhua.php?type=cate_khlb')

t=outlookbar.addtitle('内容优化','SEO优化',1)
outlookbar.additem('新闻资讯',t,'seo_youhua.php?type=cate_con_xwzx')
outlookbar.additem('模板案例',t,'seo_youhua.php?type=cate_con_mbal')
outlookbar.additem('网站建设',t,'seo_youhua.php?type=cate_con_wzjs')
outlookbar.additem('客户列表',t,'seo_youhua.php?type=cate_con_khlb')
*/

t=outlookbar.addtitle('系统留言','内容管理',1)
outlookbar.additem('留言列表',t,'manager.php?type=meslist')
outlookbar.additem('已处理留言',t,'manager.php?type=meslist&tt=2')
outlookbar.additem('未处理留言',t,'manager.php?type=meslist&tt=1')

t=outlookbar.addtitle('商品管理','产品管理',1)
outlookbar.additem('商品列表',t,'goods.php?type=goods_list')
outlookbar.additem('添加商品',t,'goods.php?type=goods_info')
outlookbar.additem('批量添加',t,'goods.php?type=batch_add')
outlookbar.additem('分类列表',t,'goods.php?type=cate_list')
outlookbar.additem('添加分类',t,'goods.php?type=cate_info')
outlookbar.additem('品牌列表',t,'goods.php?type=band_list')
outlookbar.additem('添加品牌',t,'goods.php?type=band_info')
outlookbar.additem('用户评论',t,'goods.php?type=comment_list')
//outlookbar.additem('回收站',t,'goods.php?type=recycle')

t=outlookbar.addtitle('订单管理','产品管理',1)
outlookbar.additem('订单列表',t,'goods_order.php?type=list')
outlookbar.additem('发货单列表',t,'goods_order.php?type=list&tt=delivery&status=222')
outlookbar.additem('退货单列表',t,'goods_order.php?type=list&tt=back&status=3')


//t=outlookbar.addtitle('报表统计','报表统计',1)
//outlookbar.additem('关键字分析',t,'stats.php?type=index')


t=outlookbar.addtitle('单页管理','其他扩展',1)
outlookbar.additem('系统分类',t,'con_default.php?type=catelist')
outlookbar.additem('添加分类',t,'con_default.php?type=cateadd')
outlookbar.additem('系统文章列表',t,'con_default.php?type=newlist')
outlookbar.additem('添加系统文章',t,'con_default.php?type=newadd')

t=outlookbar.addtitle('友情链接','其他扩展',1)
outlookbar.additem('列表展示',t,'friendlink.php?type=list')
outlookbar.additem('添加链接',t,'friendlink.php?type=add')

t=outlookbar.addtitle('广告设置','其他扩展',1)
outlookbar.additem('广告列表',t,'ads.php?type=adslist')
outlookbar.additem('广告标签',t,'ads.php?type=adstaglist')
outlookbar.additem('添加标签',t,'ads.php?type=adstag_add')
outlookbar.additem('添加广告',t,'ads.php?type=ads_add')

t=outlookbar.addtitle('定义导航','其他扩展',1)
outlookbar.additem('导航栏列表',t,'systemconfig.php?type=nav_list')
outlookbar.additem('添加导航栏',t,'systemconfig.php?type=nav_add')

t=outlookbar.addtitle('旗下网站','其他扩展',1)
outlookbar.additem('添加旗下网站',t,'systemconfig.php?type=other_site_info')
outlookbar.additem('旗下网站列表',t,'systemconfig.php?type=other_site_list')

/*t=outlookbar.addtitle('内容导航','管理首页',1)
outlookbar.additem('新闻资讯',t,'con_new.php?type=newlist')
outlookbar.additem('模板案例',t,'con_case.php?type=newlist')
outlookbar.additem('网站建设',t,'con_website.php?type=newlist')
outlookbar.additem('建站套餐',t,'con_website.php?type=cateedit&id=32')
outlookbar.additem('客户列表',t,'con_clientlist.php?type=newlist')*/
/*outlookbar.additem('网站信息',t,'systemconfig.php?type=basic')*/

/*t=outlookbar.addtitle('快捷方式','管理首页',1)
outlookbar.additem('网站设置',t,'')
outlookbar.additem('参数设置',t,'')*/


/*t=outlookbar.addtitle('分类导航','管理首页',1)
outlookbar.additem('新闻资讯分类',t,'con_new.php?type=catelist')
outlookbar.additem('模板案例分类',t,'con_case.php?type=catelist')
outlookbar.additem('客户列表分类',t,'con_clientlist.php?type=catelist')

t=outlookbar.addtitle('退出系统','管理首页',1)
outlookbar.additem('点击退出登录',t,'logout.php')

t=outlookbar.addtitle('关于我们','管理首页',1)
outlookbar.additem('关于我们',t,'con_default.php?type=newlist')*/
