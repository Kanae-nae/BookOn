<?php
    // 実際に扱うDBです！こっちのDBを操作する際は慎重に
    const SERVER = 'mysql323.phy.lolipop.lan';
    const DBNAME = 'LAA1658836-bookon';
    const USER = 'LAA1658836';
    const PASS = 'passbookon';

    // 個人的に作ったテスト用DB
    // 実際のDBに入れ込むには緊張する…という場合に
    // (千綿さんのDBを手動でインポートしているため、実際のDBとずれが生じるかもしれません)
    // const SERVER = 'mysql324.phy.lolipop.lan';
    // const DBNAME = 'LAA1683626-bookontest';
    // const USER = 'LAA1683626';
    // const PASS = 'passbookon';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
?>