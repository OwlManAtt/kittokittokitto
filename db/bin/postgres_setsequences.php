<?php
/*
* Used post-MySQL import to set the values of the sequences properly.
*
* I ran this from the Kitto root, and it Just Worked. It should reverse-
* engineer all of your tables, find their PK, and set the sequence's current
* value to MAX(pk) with called = true (so it goes +1 when you call nextval).
**/
require_once('includes/config.inc.php');

$db = DB::connect($APP_CONFIG['db_dsn']);
if(PEAR::isError($db))
{
    die('Could not connect.');
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);

$res = $db->query("
    SELECT 
        pg_class.relname AS tablename,
        pg_attribute.attname AS pk_column,
        REPLACE(REPLACE(pg_attrdef.adsrc,'nextval(''',''),'''::regclass)','') AS sequence_name
        FROM pg_index 
        INNER JOIN pg_class ON pg_index.indrelid = pg_class.oid 
        INNER JOIN pg_attribute ON (
            pg_class.oid = pg_attribute.attrelid 
            AND pg_index.indkey[0] = pg_attribute.attnum
        ) 
        INNER JOIN pg_attrdef ON (
            pg_class.oid = pg_attrdef.adrelid 
            AND pg_attribute.attnum = pg_attrdef.adnum
        ) 
        WHERE indisprimary = true 
        AND pg_class.relkind = 'r'
");
if(PEAR::isError($res))
{
    die("#1 - {$res->getDebugInfo()}");
}

while($res->fetchInto($ROW))
{
    $max = $db->getOne("SELECT MAX(\"{$ROW['pk_column']}\") FROM \"{$ROW['tablename']}\"");
    if(PEAR::isError($max))
    {
        print "Error: {$max->getDebugInfo()}";
        continue;
    }

    $max = (int)$max;
    if($max === 0)
    {
        $max = 1;
    }

    $reset = $db->query("SELECT setval('{$ROW['sequence_name']}',$max,true)");
    if(PEAR::isError($reset))
    {
        print "Could not set value for {$ROW['sequence_name']}: {$reset->getDebugInfo()}";
    }  
}

$db->disconnect();
?>
