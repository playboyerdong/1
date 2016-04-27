<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/11/5
 * Time: 23:47*/
header('content-type:text/html;charset=utf8');
//本次学习实例化redis,并且连接redis,进行简单的读取和设置数据
//实例化redis
$redis = new Redis();
//连接redis
$redis->connect('192.168.1.110',6379);
$redis->set('lakers','Kobe Bryant');
echo $redis->get('lakers');

//keys 查找符合给定模式的key
echo '<br><br><font color="#ff6699">keys的用法</font><br>';
$array_team = array('news'=>'delong','houston'=>'harden','minnesota'=>'jianeite');
$redis->mset($array_team);
print_r($redis->keys('n*'));
print_r($redis->keys('*n*'));
print_r($redis->keys('*'));

//randomkey 随机返回一个key
echo '<br><br><font color="#ff6699">randomkey的用法</font><br>';
echo $redis->randomKey();

//ttl 返回给定key的剩余时间（以秒为单位）
echo '<br><br><font color="#ff6699">ttl的用法</font><br>';
var_dump($redis->ttl('lakers'));
$redis->set('bull','rose',30);
var_dump($redis->ttl('bull'));

//exists 检查给定的key是否存在
echo '<br><br><font color="#ff6699">exists的用法</font><br>';
var_dump($redis->exists('bull'));
$redis->del('bull');
var_dump($redis->exists('bull'));

//move 将当前数据库(默认为0)的key移动到其他数据库中
//如果当前数据库和目标数据库有相同的给定的key,或者key不存在当前的数据库，那么move没有任何效果
echo '<br><br><font color="#ff6699">move的用法</font><br>';
//情况1:key存在当前数据库
$redis->select(0); //redis默认使用数据库为0，这里为了显示清楚，再指定一次
$redis->set('kobe','24');
var_dump($redis->move('kobe',1)); //把kobe移动到数据库1中，bool(true)

//情况2:当key不存在的时候
$redis->select(1); //选中1的数据库
var_dump($redis->exists('nash')); //bool(false)
var_dump($redis->move('nash',0)); //bool(false)

//情况3:当源数据库中和目标数据库中有相同的key
$redis->select(0);
$redis->set('kobe',8);

$redis->select(1);
$redis->set('kobe',24);

$redis->select(0);
$redis->move('kobe',1);
echo $redis->get('kobe')."  ";

$redis->select(1);
echo $redis->get('kobe');

//rename 将key的名称修改为newkey
//当key和newkey相同或key不存在时，返回一个错误
//当newkey已经存在时，newkey将替换旧值
echo '<br><br><font color="#ff6699">rename的用法</font><br>';

# 情况1：key存在且newkey不存在
$redis->SET('message',"hello world");
var_dump($redis->RENAME('message','greeting'));  //bool(true)
var_dump($redis->EXISTS('message'));  # message不复存在 //bool(false)
var_dump($redis->EXISTS('greeting'));   # greeting取而代之 //bool(true)

# 情况2：当key不存在时，返回错误 ,php返回false;
var_dump($redis->RENAME('fake_key','never_exists'));  //bool(false)

# 情况3：newkey已存在时，RENAME会覆盖旧newkey
$redis->SET('pc',"lenovo");
$redis->SET('personal_computer',"dell");
var_dump($redis->RENAME('pc','personal_computer')); //bool(true)
var_dump($redis->GET('pc')); //(nil)   bool(false)
var_dump($redis->GET('personal_computer'));  # dell“没有”了 //string(6) "lenovo"

//renamenx 当且仅当newkey不存在时，将key改为newkey
echo '<br><br><font color="#ff6699">renamenx的用法</font><br>';

# 情况1：newkey不存在，成功
$redis->SET('player',"MPlyaer");
$redis->EXISTS('best_player'); //int(0)
var_dump($redis->RENAMENX('player','best_player')); // bool(true)

# 情况2：newkey存在时，失败
$redis->SET('animal',"bear");
$redis->SET('favorite_animal', "butterfly");

var_dump($redis->RENAMENX('animal', 'favorite_animal'));// bool(false)

var_dump($redis->get('animal')); //string(4) "bear"
var_dump($redis->get('favorite_animal')); //string(9) "butterfly"


//type 返回key所存储的数值的类型
/*
 *  none(key不存在)int(0)
 *  string(字符串)int(1)
 *  list(列表)int(2)
 *  set(集合)int(3)
 *  zset(有序集)int(4)
 *  hash(哈希表)int(5)
 */
echo '<br><br><font color="#ff6699">type的用法</font><br>';
var_dump($redis->TYPE('fake_key')); //none /int(0)

$redis->SET('weather',"sunny");  # 构建一个字符串
var_dump($redis->TYPE('weather'));//string / int(1)

$redis->SADD('pat',"dog");  # 构建一个集合
var_dump($redis->TYPE('pat')); //set /int(2)

$redis->LPUSH('book_list',"programming in scala");  # 构建一个列表
var_dump($redis->TYPE('book_list'));//list / int(3)

$redis->ZADD('pats',1,'cat');  # 构建一个zset (sorted set) // int(1)
$redis->ZADD('pats',2,'dog');
$redis->ZADD('pats',3,'pig');
var_dump($redis->zRange('pats',0,-1)); // array(3) { [0]=> string(3) "cat" [1]=> string(3) "dog" [2]=> string(3) "pig" }
var_dump($redis->TYPE('pats')); //zset / int(4)

$redis->HSET('website','google','www.g.cn');   # 一个新域
var_dump($redis->HGET('website','google')); //string(8) "www.g.cn"
var_dump($redis->TYPE('website')); //hash /int(5)


//expire 为给定的key设置有效期
echo '<br><br><font color="#ff6699">expire的用法</font><br>';
$redis->set('lakers','kobe');
$redis->expire('lakers',30); //设置lakers的过期时间为30秒
echo "设置lakers的过期时间为30秒";
//expireat 和expire一样，为给定的key设置有效期，不同的是，接受的时间参数是unix时间戳
echo '<br><br><font color="#ff6699">expireat的用法</font><br>';
$redis->set('lakers','kobe');
$redis->expire('lakers',1355292000); //设置lakers的过期时间2012.12.12日
echo "设置lakers的过期时间为2012.12.12";

//object 允许从内部查看给定key的Redis对象
/*它通常用在除错(debugging)或者了解为了节省空间而对key使用特殊编码的情况。
当将Redis用作缓存程序时，你也可以通过OBJECT命令中的信息，决定key的驱逐策略(eviction policies)。
OBJECT命令有多个子命令：

OBJECT REFCOUNT <key>返回给定key引用所储存的值的次数。此命令主要用于除错。
OBJECT ENCODING <key>返回给定key锁储存的值所使用的内部表示(representation)。
OBJECT IDLETIME <key>返回给定key自储存以来的空转时间(idle， 没有被读取也没有被写入)，以秒为单位。
对象可以以多种方式编码：
字符串可以被编码为raw(一般字符串)或int(用字符串表示64位数字是为了节约空间)。
列表可以被编码为ziplist或linkedlist。ziplist是为节约大小较小的列表空间而作的特殊表示。
集合可以被编码为intset或者hashtable。intset是只储存数字的小集合的特殊表示。
哈希表可以编码为zipmap或者hashtable。zipmap是小哈希表的特殊表示。
有序集合可以被编码为ziplist或者skiplist格式。ziplist用于表示小的有序集合，而skiplist则用于表示任何大小的有序集合。
假如你做了什么让Redis没办法再使用节省空间的编码时(比如将一个只有1个元素的集合扩展为一个有100万个元素的集合)，特殊编码类型(specially encoded types)会自动转换成通用类型(general type)。*/
echo '<br><br><font color="#ff6699">object的用法</font><br>';
$redis->set('lakers','24');
var_dump($redis->object('REFCOUNT','lakers'));

//sleep(5);
echo $redis->OBJECT('IDLETIME','lakers');  # 等待一阵。。。然后查看空转时间 //(integer) 10
var_dump($redis->OBJECT('ENCODING','lakers'));  # 字符串的编码方式 //string(3) "raw"
$redis->SET('phone',15820123123);  # 大的数字也被编码为字符串
var_dump($redis->OBJECT('ENCODING','phone')); //string(3) "int"
$redis->SET('age',20);  # 短数字被编码为int
var_dump($redis->OBJECT('ENCODING','age')); //string(3) "int"


//persist 移除给定的key的有效期
echo '<br><br><font color="#ff6699">persist的用法</font><br>';
$redis->expire('lakers',30);
echo $redis->ttl('lakers')."  ";
$redis->persist('lakers');
echo $redis->ttl('lakers');


//sort 返回或保存给定列表、集合、有序集合key中经过排序的元素
/* 参数
 * array(
‘by’ => ‘some_pattern_*’,
‘limit’ => array(0, 1),
‘get’ => ‘some_other_pattern_*’ or an array of patterns,
‘sort’ => ‘asc’ or ‘desc’,
‘alpha’ => TRUE,
‘store’ => ‘external-key’
)*/
echo '<br><br><font color="#ff6699">sort的用法</font><br>';
$redis->flushAll();
$redis->lPush('lakerss',24);
$redis->lPush('lakerss',2);
$redis->lPush('lakerss',6);
var_dump($redis->sort('lakerss',array('sort'=>'desc')));

echo "<br>";
$redis->flushAll();
# 将数据一一加入到列表中
$redis->LPUSH('website', "www.reddit.com");
$redis->LPUSH('website', "www.slashdot.com");
$redis->LPUSH('website', "www.infoq.com");
# 默认排序
var_dump($redis->SORT('website'));//bool(false)

# 按字符排序 ALPHA=true
var_dump($redis->SORT('website', array('ALPHA'=>TRUE))); //array(3) { [0]=> string(13) "www.infoq.com" [1]=> string(14) "www.reddit.com" [2]=> string(16) "www.slashdot.com" }

echo '<br>';
$redis->flushAll();
# 将数据一一加入到列表中
$redis->LPUSH('rank', 30); //(integer) 1
$redis->LPUSH('rank', 56); //(integer) 2
$redis->LPUSH('rank', 42); //(integer) 3
$redis->LPUSH('rank', 22); //(integer) 4
$redis->LPUSH('rank', 0);  //(integer) 5
$redis->LPUSH('rank', 11); //(integer) 6
$redis->LPUSH('rank', 32); //(integer) 7
$redis->LPUSH('rank', 67); //(integer) 8
$redis->LPUSH('rank', 50); //(integer) 9
$redis->LPUSH('rank', 44); //(integer) 10
$redis->LPUSH('rank', 55); //(integer) 11

# 排序
$redis_sort_option=array('LIMIT'=>array(0,5));
var_dump($redis->SORT('rank',$redis_sort_option));   # 返回排名前五的元素 // array(5) { [0]=> string(1) "0" [1]=> string(2) "11" [2]=> string(2) "22" [3]=> string(2) "30" [4]=> string(2) "32" }

$redis_sort_option=array(
    'LIMIT'=>array(0,5),
    'SORT'=>'DESC'
);
var_dump($redis->SORT('rank',$redis_sort_option)); //array(5) { [0]=> string(2) "67" [1]=> string(2) "56" [2]=> string(2) "55" [3]=> string(2) "50" [4]=> string(2) "44" }

echo "<br>";

//使用外部key进行排序 有时候你会希望使用外部的key来作为权重比较元素，代替默认的对比方法。
//假设现在有用户(user)的数据：id,name,level

#先将要使用的数据加入到数据库中
#admin
$redis->lPush('user_id',1);
$redis->set('user_name_1','admin');
$redis->set('user_level_1',9999);

#user1
$redis->lPush('user_id',2);
$redis->set('user_name_2','user1');
$redis->set('user_level_2',2222);

#user2
$redis->lPush('user_id',3);
$redis->set('user_name_3','user2');
$redis->set('user_level_3',111);

print_r($redis->sort('user_id')); //按照默认的排序方式

#如果希望按照level从大到小排序，可以这样:
echo "<br>";
$sort_option = array('by'=>'user_level_*','sort'=>'asc');
print_r($redis->sort('user_id',$sort_option));

#有时候除了返回user_id,还需要返回user_name,可以这样写
echo "<br>";
$sort_option = array('by'=>'user_level_*','sort'=>'asc','get'=>'user_name_*');
print_r($redis->sort('user_id',$sort_option));


//set 将字符串value值关联到key 如果已经存在key，覆盖操作
echo '<br><br><font color="#ff6699">set的用法</font><br>';
$redis->set('lakers','kobe');
echo $redis->get('lakers');

//setnx 当且仅当key不存在时，设置value，不进行覆盖操作
echo '<br><br><font color="#ff6699">setnx的用法</font><br>';
$redis->setnx('lakers','paul');#因为lakers已经存在，所以设置失败
echo $redis->get('lakers'); #kobe

//setex 将值value关联到key，并将key的有效时间设置为seconds(秒为单位)。如果key存在，覆盖操作
echo '<br><br><font color="#ff6699">setex的用法</font><br>';
$redis->setex('lakers',30,'kobe');

//setrange 用value参数覆写(Overwrite)给定key所储存的字符串值，从偏移量offset开始。

/*不存在的key当作空白字符串处理。

SETRANGE命令会确保字符串足够长以便将value设置在指定的偏移量上，如果给定key原来储存的字符串长度比偏移量小(比如字符串只有5个字符长，但你设置的offset是10)，那么原字符和偏移量之间的空白将用零比特(zerobytes,"\x00")来填充。

注意你能使用的最大偏移量是2^29-1(536870911)，因为Redis的字符串被限制在512兆(megabytes)内。如果你需要使用比这更大的空间，你得使用多个key。*/
echo '<br><br><font color="#ff6699">setrange的用法</font><br>';
#情况1 对非空字符串进行setrange
$redis->set('lakers','I love kobe');
$redis->setRange('lakers',7,'haha');
echo $redis->get('lakers');
#情况2 对空字符串/不存在的key进行setrange
$redis->setRange('dsjfdsf',5,'setrange');
var_dump($redis->get('dsjfdsf'));


//mset 同时设置一个或多个key-value对,如果key存在，覆盖操作
echo '<br><br><font color="#ff6699">mset的用法</font><br>';
$array = array('player1'=>'kobe','player2'=>'james','player3'=>'paul');
$redis->mset($array);
print_r($redis->keys('*'));

//msetnx 当且仅当key不存在时，设置一个或多个key-value对，如果key存在，不覆盖
echo '<br><br><font color="#ff6699">msetnx的用法</font><br>';
$array = array('player1'=>'kobe','player2'=>'james','player3'=>'paul');
var_dump($redis->msetnx($array)); #bool(false)


//append 如果key存在，将value追加到原来的值后面 反之，直接类似set命令
echo '<br><br><font color="#ff6699">append的用法</font><br>';
$redis->set('lakers','kobe');
$redis->append('lakers',' is a best player');
echo $redis->get('lakers');

//get 返回key所关联的字符串值 如果key不存在，返回特殊值nil 假如key的值不是字符串类型，返回错误，因为get只能处理字符串的值
echo '<br><br><font color="#ff6699">get的用法</font><br>';
echo $redis->get('lakers');

//mget 返回所有(一个或多个)给定key的值 key不存在，返回nil
echo '<br><br><font color="#ff6699">mget的用法</font><br>';
$array = array('player1','player2');
var_dump($redis->mget($array));


//getrange 同时设置一个或多个key-value对,如果key存在，覆盖操作
echo '<br><br><font color="#ff6699">getrange的用法</font><br>';
/*返回key中字符串值的子字符串，字符串的截取范围由start和end两个偏移量决定(包括start和end在内)。

负数偏移量表示从字符串最后开始计数，-1表示最后一个字符，-2表示倒数第二个，以此类推。

GETRANGE通过保证子字符串的值域(range)不超过实际字符串的值域来处理超出范围的值域请求。*/
$redis->SET('greeting', "hello, my friend");
echo $redis->GETRANGE('greeting', 0, 4).'<br>';  # 返回索引0-4的字符，包括4。 //"hello"
echo $redis->GETRANGE('greeting', -1 ,-5).'<br>';  # 不支持回绕操作  //""
echo $redis->GETRANGE('greeting', -3 ,-1).'<br>';  # 负数索引 //"end"
echo $redis->GETRANGE('greeting', 0, -1).'<br>';  # 从第一个到最后一个 //"hello, my friend"
echo $redis->GETRANGE('greeting', 0, 1008611).'<br>';  # 值域范围不超过实际字符串，超过部分自动被符略 //"hello, my friend"

//getset 将给定的key的值设置为value,并返回key的旧值
echo '<br><br><font color="#ff6699">getset的用法</font><br>';
var_dump($redis->EXISTS('mail'));//return bool(false);
var_dump($redis->GETSET('mail','xxx@google.com'));  # 因为mail之前不存在，没有旧值，返回nil ,#(nil)   //bool(false)

var_dump($redis->GETSET('mail','xxx@yahoo.com'));  # mail被更新，旧值被返回 //string(14) "xxx@google.com"

/*GETSET可以和INCR组合使用，实现一个有原子性(atomic)复位操作的计数器(counter)。

举例来说，每次当某个事件发生时，进程可能对一个名为mycount的key调用INCR操作，通常我们还要在一个原子时间内同时完成获得计数器的值和将计数器值复位为0两个操作。*/
$redis->incr('mycount').'<br>';
if($redis->get('count') > 5){
    echo $redis->getSet('count',0);
}
echo $redis->get('count');


//strlen 返回给定key所存储值的字符串的长度 不存在key,返回0
echo '<br><br><font color="#ff6699">strlen的用法</font><br>';
echo $redis->strlen('lakers');


//incr 将key中存储的数字值增加1 当key不存在时，按0处理 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
echo '<br><br><font color="#ff6699">incr的用法</font><br>';
$redis->incr('kobe');
echo $redis->get('kobe');


//incrby 将key存储的值加上增量increment 当key不存在，按0处理
echo '<br><br><font color="#ff6699">incrby的用法</font><br>';
# 情况1：key存在且是数字值
$redis->SET('rank', 50);  # 设置rank为50
$redis->INCRBY('rank', 20);  # 给rank加上20
var_dump($redis->GET('rank')); #"70"   //string(2) "70"

# 情况2：key不存在
$redis->EXISTS('counter'); //bool(false)
$redis->INCRBY('counter','30'); #int 30  //bool(false)
var_dump($redis->GET('counter')); #30 //经测试 与手册上结果不一样，不能直接从bool型转为int型。 return bool(false)

# 情况3：key不是数字值
$redis->SET('book', "long long ago...");
var_dump($redis->INCRBY('book', 200)); #(error) ERR value is not an integer or out of range   // bool(false)


//decr 将key中存储的数字值减1 当key不存在时，按0处理 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
echo '<br><br><font color="#ff6699">decr的用法</font><br>';

# 情况1：对存在的数字值key进行DECR
$redis->SET('failure_times', 10);
$redis->DECR('failure_times'); //int(9)
echo $redis->GET('failure_times').'<br>';  //string(1) "9"

# 情况2：对不存在的key值进行DECR
$redis->EXISTS('count'); #(integer) 0 //bool(false)
$redis->DECR('count');  //int(-1)
echo $redis->GET('count').'<br>'; //string(2) "-1"

# 情况3：对存在但不是数值的key进行DECR
$redis->SET('company', 'YOUR_CODE_SUCKS.LLC');
var_dump($redis->DECR('company')); #(error) ERR value is not an integer or out of range   //bool(false)
echo $redis->GET('company').'<br>'; //YOUR_CODE_SUCKS.LLC

//decrby 将key存储的值减去减量decrement 当key不存在，按0处理
echo '<br><br><font color="#ff6699">decrby的用法</font><br>';

# 情况1：对存在的数值key进行DECRBY
$redis->SET('count', 100);
var_dump($redis->DECRBY('count', 20)); //int(80)
var_dump($redis->GET('count'));  //string(2) "80"

# 情况2：对不存在的key进行DECRBY
$redis->EXISTS('pages');#(integer) 0  //bool(false)
var_dump($redis->DECRBY('pages', 10));  //int(-10)
var_dump($redis->GET('pages')); //string(3) "-10"