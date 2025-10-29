<?php
    // 実際に扱うDBです！DBを操作する際は慎重に
    // (別のDBでテストしたい場合はこのファイルの中身を変更、分からない人はいじらなくて良い)
    const SERVER = 'myspl323.phy.lolipop.lan';
    const DBNAME = 'LAA1658836-bookon';
    const USER = 'LAA1658836';
    const PASS = 'passbookon';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
?>