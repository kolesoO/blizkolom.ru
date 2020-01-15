1) Перенести все компании в новую таблицу

insert INTO companies (created_at, updated_at, name, code, active, title, description, keywords, h1, preview_text, detail_text, preview_picture, detail_picture, contacts, url, phone, email, map_coords)
SELECT TIMESTAMP_X, TIMESTAMP_X, NAME, CODE, 1, SEO_TITLE, SEO_DESCRIPTION, SEO_KEYWORDS, SEO_H1, PREVIEW_TEXT, DETAIL_TEXT, PREVIEW_PICTURE, DETAIL_PICTURE, CONTACTS, URL, PHONE, EMAIL, MAP_COORDS
from blizko.COMPANIES


2) Привязка всех компаний к региону Россия

INSERT INTO company_properties (company_id, property_id)
select id, 7 from companies


3) Привязка компаний к региону Москва

INSERT INTO company_properties (company_id, property_id)
select id, 8 from companies where name in 
(select name from blizko.COMPANIES where REGION_ID=20)


4) Привязка компаний к региону Спб

INSERT INTO company_properties (company_id, property_id)
select id, 9 from companies where name in 
(select name from blizko.COMPANIES where REGION_ID=1)


5) Привязка компаний к региону Екатеринбург

INSERT INTO company_properties (company_id, property_id)
select id, 41 from companies where name in 
(select name from blizko.COMPANIES where REGION_ID=2)


6) Привязка компаний к региону Новосибирск

INSERT INTO company_properties (company_id, property_id)
select id, 42 from companies where name in 
(select name from blizko.COMPANIES where REGION_ID=3)


7) Привязка компаний к региону Ростов-на-Дону

INSERT INTO company_properties (company_id, property_id)
select id, 43 from companies where name in 
(select name from blizko.COMPANIES where REGION_ID=9)


8) Привязка компаний к классу лома 3A

INSERT INTO company_properties (company_id, property_id)
select id, 21 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%0%')


9) Привязка компаний к классу лома 5A

INSERT INTO company_properties (company_id, property_id)
select id, 22 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%;1;%')


10) Привязка компаний к классу лома 12A

INSERT INTO company_properties (company_id, property_id)
select id, 23 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%;2%')


11) Привязка компаний к классу лома 16A

INSERT INTO company_properties (company_id, property_id)
select id, 24 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%;3%')


12) Привязка компаний к классу лома 17A

INSERT INTO company_properties (company_id, property_id)
select id, 25 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%;4%')


13) Привязка компаний к классу лома Стальной лом 3А

INSERT INTO company_properties (company_id, property_id)
select id, 44 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%12%')


14) Привязка компаний к классу лома Стальной лом 5А

INSERT INTO company_properties (company_id, property_id)
select id, 45 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%13%')


15) Привязка компаний к классу лома Стальной лом 12А

INSERT INTO company_properties (company_id, property_id)
select id, 46 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%14%')


16) Привязка компаний к классу лома Стружка 16А

INSERT INTO company_properties (company_id, property_id)
select id, 47 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%15%')


16) Привязка компаний к классу лома Чугунный лом 17А

INSERT INTO company_properties (company_id, property_id)
select id, 48 from companies where name in 
(select name from blizko.COMPANIES where CLASS_ID LIKE '%16%')


17) Привязка компаний к типу изделий Аккумуляторы

INSERT INTO company_properties (company_id, property_id)
select id, 27 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '0;%')


18) Привязка компаний к типу изделий Электродвигатели

INSERT INTO company_properties (company_id, property_id)
select id, 28 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;1;%' OR TYPE_ID LIKE '%;1')


19) Привязка компаний к типу изделий Катализаторы

INSERT INTO company_properties (company_id, property_id)
select id, 29 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;2;%' OR TYPE_ID LIKE '%;2')


20) Привязка компаний к типу изделий Радиодетали

INSERT INTO company_properties (company_id, property_id)
select id, 30 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;3;%' OR TYPE_ID LIKE '%;3')


21) Кабели

INSERT INTO company_properties (company_id, property_id)
select id, 31 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;6;%' OR TYPE_ID LIKE '%;6')


22) Автомобили

INSERT INTO company_properties (company_id, property_id)
select id, 32 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;7;%' OR TYPE_ID LIKE '%;7')


23) Подшипники

INSERT INTO company_properties (company_id, property_id)
select id, 33 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;8;%' OR TYPE_ID LIKE '%;8')


24) Трансформаторы

INSERT INTO company_properties (company_id, property_id)
select id, 34 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;9;%' OR TYPE_ID LIKE '%;9')


25) Железнодорожный

INSERT INTO company_properties (company_id, property_id)
select id, 35 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;10;%' OR TYPE_ID LIKE '%;10')


26) Жесть

INSERT INTO company_properties (company_id, property_id)
select id, 36 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;11;%' OR TYPE_ID LIKE '%;11')


27) АКБ 55Ah

INSERT INTO company_properties (company_id, property_id)
select id, 49 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;12;%' OR TYPE_ID LIKE '%;12')


28) АКБ 75Ah

INSERT INTO company_properties (company_id, property_id)
select id, 50 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;13;%' OR TYPE_ID LIKE '%;13')


29) АКБ 100Ah

INSERT INTO company_properties (company_id, property_id)
select id, 51 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;14;%' OR TYPE_ID LIKE '%;14')


30) АКБ 132Ah

INSERT INTO company_properties (company_id, property_id)
select id, 52 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;15;%' OR TYPE_ID LIKE '%;15')


31) АКБ 190Ah

INSERT INTO company_properties (company_id, property_id)
select id, 53 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;16;%' OR TYPE_ID LIKE '%;16')


32) Отечественные

INSERT INTO company_properties (company_id, property_id)
select id, 54 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;18;%' OR TYPE_ID LIKE '%;18')


33) Импортные

INSERT INTO company_properties (company_id, property_id)
select id, 55 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;19;%' OR TYPE_ID LIKE '%;19')


34) Металлические

INSERT INTO company_properties (company_id, property_id)
select id, 56 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;20;%' OR TYPE_ID LIKE '%;20')


35) Сажевые

INSERT INTO company_properties (company_id, property_id)
select id, 57 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;21;%' OR TYPE_ID LIKE '%;21')


36) Керамические

INSERT INTO company_properties (company_id, property_id)
select id, 58 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;22;%' OR TYPE_ID LIKE '%;22')


37) Компьютерные

INSERT INTO company_properties (company_id, property_id)
select id, 59 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;4;%' OR TYPE_ID LIKE '%;4')


38) Электронные

INSERT INTO company_properties (company_id, property_id)
select id, 60 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;5;%' OR TYPE_ID LIKE '%;5')


39) Микросхемы

INSERT INTO company_properties (company_id, property_id)
select id, 61 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;23;%' OR TYPE_ID LIKE '%;23')


39) Транзисторы

INSERT INTO company_properties (company_id, property_id)
select id, 62 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;24;%' OR TYPE_ID LIKE '%;24')


40) Резисторы

INSERT INTO company_properties (company_id, property_id)
select id, 63 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;25;%' OR TYPE_ID LIKE '%;25')


41) Реле

INSERT INTO company_properties (company_id, property_id)
select id, 64 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;26;%' OR TYPE_ID LIKE '%;26')


41) Конденсаторы

INSERT INTO company_properties (company_id, property_id)
select id, 65 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;27;%' OR TYPE_ID LIKE '%;27')


42) Медный очищ.

INSERT INTO company_properties (company_id, property_id)
select id, 66 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;40;%' OR TYPE_ID LIKE '%;40')


43) Медный неочищ.

INSERT INTO company_properties (company_id, property_id)
select id, 67 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;41;%' OR TYPE_ID LIKE '%;41')


44) Алюмин. очищ.

INSERT INTO company_properties (company_id, property_id)
select id, 68 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;42;%' OR TYPE_ID LIKE '%;42')


45) Алюмин. очищ.

INSERT INTO company_properties (company_id, property_id)
select id, 69 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;43;%' OR TYPE_ID LIKE '%;43')


46) Свинцовый очищ.

INSERT INTO company_properties (company_id, property_id)
select id, 70 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;44;%' OR TYPE_ID LIKE '%;44')


47) Свинц. неочищ.

INSERT INTO company_properties (company_id, property_id)
select id, 71 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;45;%' OR TYPE_ID LIKE '%;45')


48) Радиальные

INSERT INTO company_properties (company_id, property_id)
select id, 72 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;46;%' OR TYPE_ID LIKE '%;46')


49) Игольчатые

INSERT INTO company_properties (company_id, property_id)
select id, 74 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;48;%' OR TYPE_ID LIKE '%;48')


50) Упорные подшип.

INSERT INTO company_properties (company_id, property_id)
select id, 75 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;49;%' OR TYPE_ID LIKE '%;49')


51) Алюминиевые тр.

INSERT INTO company_properties (company_id, property_id)
select id, 76 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;50;%' OR TYPE_ID LIKE '%;50')


52) Медные трансф.

INSERT INTO company_properties (company_id, property_id)
select id, 77 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;51;%' OR TYPE_ID LIKE '%;51')


53) Колесные пары

INSERT INTO company_properties (company_id, property_id)
select id, 78 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;52;%' OR TYPE_ID LIKE '%;52')


54) Рамы боковые

INSERT INTO company_properties (company_id, property_id)
select id, 79 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;53;%' OR TYPE_ID LIKE '%;53')


55) Балки подрессор.

INSERT INTO company_properties (company_id, property_id)
select id, 80 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;54;%' OR TYPE_ID LIKE '%;54')


56) Рельсы

INSERT INTO company_properties (company_id, property_id)
select id, 81 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;55;%' OR TYPE_ID LIKE '%;55')


57) Черная жесть

INSERT INTO company_properties (company_id, property_id)
select id, 82 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;56;%' OR TYPE_ID LIKE '%;56')


58) Белая жесть

INSERT INTO company_properties (company_id, property_id)
select id, 83 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;57;%' OR TYPE_ID LIKE '%;57')


59) Пищевая жесть

INSERT INTO company_properties (company_id, property_id)
select id, 84 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;58;%' OR TYPE_ID LIKE '%;58')


60) Материн. платы

INSERT INTO company_properties (company_id, property_id)
select id, 85 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;28;%' OR TYPE_ID LIKE '%;28')


61) Видеокарты

INSERT INTO company_properties (company_id, property_id)
select id, 86 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;29;%' OR TYPE_ID LIKE '%;29')


62) Блоки питания

INSERT INTO company_properties (company_id, property_id)
select id, 87 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;30;%' OR TYPE_ID LIKE '%;30')


61) Процессоры

INSERT INTO company_properties (company_id, property_id)
select id, 88 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;31;%' OR TYPE_ID LIKE '%;31')


62) Жесткие диски

INSERT INTO company_properties (company_id, property_id)
select id, 89 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;32;%' OR TYPE_ID LIKE '%;32')


63) Оператив. память

INSERT INTO company_properties (company_id, property_id)
select id, 90 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;33;%' OR TYPE_ID LIKE '%;33')


64) Блоки GSM

INSERT INTO company_properties (company_id, property_id)
select id, 91 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;34;%' OR TYPE_ID LIKE '%;34')


65) Материн. платы

INSERT INTO company_properties (company_id, property_id)
select id, 92 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;35;%' OR TYPE_ID LIKE '%;35')


66) Видеокарты

INSERT INTO company_properties (company_id, property_id)
select id, 93 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;36;%' OR TYPE_ID LIKE '%;36')


67) Оператив. память

INSERT INTO company_properties (company_id, property_id)
select id, 94 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;37;%' OR TYPE_ID LIKE '%;37')


68) Микросхемы

INSERT INTO company_properties (company_id, property_id)
select id, 95 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;38;%' OR TYPE_ID LIKE '%;38')


69) Транзисторы

INSERT INTO company_properties (company_id, property_id)
select id, 96 from companies where name in 
(select name from blizko.COMPANIES where TYPE_ID LIKE '%;39;%' OR TYPE_ID LIKE '%;39')


70) Вывоз бесплатно

INSERT INTO company_properties (company_id, property_id)
select id, 38 from companies where name in 
(select name from blizko.COMPANIES where ADDITIONAL_SERVICES LIKE '%Вывоз лома%')


71) С демонтажем

INSERT INTO company_properties (company_id, property_id)
select id, 40 from companies where name in 
(select name from blizko.COMPANIES where ADDITIONAL_SERVICES LIKE '%Демонтаж%')
