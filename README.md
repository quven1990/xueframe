# frame

搭建环境之后一定要把Runtime目录设置成可写的

并且在nginx配置中加上
location /{
    try_files $uri $uri/ /index.php?$uri&$args;
}
