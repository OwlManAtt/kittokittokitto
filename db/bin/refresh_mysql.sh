#!/bin/bash
tables=( jump_page item_class item_type pet_specie pet_specie_color cron_tab shop_restock board avatar timezone datetime_format staff_permission staff_group shop pet_specie_pet_specie_color board_category item_recipe_material item_recipe_type )

if [ "$1" = '' ]
then
    echo "o hi, pass the database password as an argument plx. thx.";
    exit 2;
fi

mysqldump -d -u root --password=$1 kitto > mysql5_ddl.sql

for table in  ${tables[@]}
do
    mysqldump -t -c -u root --password=$1 kitto $table > mysql_data/${table}.sql
done
