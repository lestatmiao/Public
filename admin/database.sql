-- 创建商品分类表
create table sh_category(
	c_id int primary key auto_increment,
	c_name varchar(10) not null comment '商品分类名字',
	c_number int unsigned default 0 comment '商品分类下的品牌数量',
	c_sort int unsigned default 50 comment '商品分类排序的权重',
	c_parent_id int default 0 comment '商品分类的父分类id:默认0表示顶级分类'
)charset utf8;

-- 获取数据：蠕虫复制
insert into sh_category
select cat_id, cat_name,0,sort_order,parent_id
from lestat_shop.ecs_category;
-- 创建商品表
create table sh_goods(
	g_id int primary key auto_increment,
	g_name varchar(20) not null comment '商品名称',
	g_price decimal(10,2) default 0.0 comment '商品价格',
	g_inv int unsigned default 0 comment '商品库存',
	g_desc text comment '商品描述',
	c_id int not null comment '商品分类',
	g_sort int not null comment '商品排序',
	g_sn varchar(20) not null unique comment '商品货号,固定成都唯一性',
-- 商品买卖信息
	g_is_on_sale tinyint default 1 comment '商品是否上架',
	g_is_best tinyint default 0 comment '商品是否为精品',
	g_is_hot tinyint default 0 comment '商品是否热销',
	g_is_new tinyint default 1 comment '商品是否为新品',
	g_clicks int unsigned default 100 comment '商品点击量',
-- 商品图片信息
	g_image text comment '商品图片名称',
	g_thumb text comment '商品缩略图名称',
	g_water text comment '商品水印图名称'
	
)charset utf8;

-- 数据：蠕虫复制
insert into sh_goods(g_name,g_price,g_inv,c_id,g_sn,g_clicks)
select
goods_name,shop_price,goods_number,cat_id,goods_sn,click_count
from lestat_shop.ecs_goods;

update sh_goods set g_is_on_sale = floor(rand()*2), g_is_best = floor(rand()*2),g_is_hot = floor(rand()*2),g_is_new=floor(rand()*2);

