<!DOCTYPE html>
<html>
<head>
    <title>Lab Framwork</title>
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
        .line {
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Lab Framework</div>
        <span class="line"><b><?php echo $this->_vars['name']; ?></b></span>
        <br/>
        <span class="line"><b>
        <?php if($this->_vars['check']){ ?>
        结果为真
        <?php }else{ ?>
        结果为假
        <?php } ?>
        </b></span><br/>
        <?php foreach($this->_vars['data'] as $key=>$value){?>
        <span class="line"><?php echo $key?>....<?php echo $value?></span> <br />
        <?php } ?>
        <?php include 'a.html';?>
        <?php /*(这里是注释内容 不出意外你应该看不到) */?><br/>

        <?php echo $this->_config['db_host'] ?>

    </div>
</div>
</body>
</html>
