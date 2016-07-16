
-- Image table
INSERT INTO images(nameImage, addressImage) VALUES("img A", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-ao-quan-ngo-19-dong-tac-avatarjpg-5138.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("img B", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-chen-doan-tran-nghiep-lau-nuong-anh-vuong2-3511.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("img C", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-nam-son-809-giai-phong-anh-vuong-4219.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("img D", "http://cdn.pasgo.vn/anh-diem-den/su-buffet-giao-thoa-am-thuc-a-au-1-2125.jpg");
-- profile image
INSERT INTO images(nameImage, addressImage) VALUES("A", "http://www.aspirehire.co.uk/aspirehire-co-uk/_img/profile.svg");
INSERT INTO images(nameImage, addressImage) VALUES("B", "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQI-zOjZs6v3V9cpwrdupviz3VzEBjZyHfF8N8b81E_GirhszEJXg");


-- Address Table
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("18 Ngõ A Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("20 Ngõ B Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("32 Ngõ C Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("42 Ngõ D Đông Tác, Quận Đống Đa", '01', '001', '00001');

-- Users Table
-- pass la "1234567", o duoi da ma hoa md5 
INSERT INTO users(firstnameUser, phoneUser, passwordUser, authorityUser, imageID, addressID) VALUES("John", "1234567", "fcea920f7412b5da7be0cf42b8c93759", 2, 5, 1);
INSERT INTO users(firstnameUser, phoneUser, passwordUser, authorityUser, imageID, addressID) VALUES("Kelly", "756321", "fcea920f7412b5da7be0cf42b8c93759", 2, 6, 2);

-- Restaurants Table
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest A", "Should be Campaign A", 40, 1, 1);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest B", "Should be Campaign B", 30, 2, 2);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest C", "Should be Campaign C", 40, 3, 1);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest D", "Should be Campaign D", 20, 4, 2);

-- Categoriesofrestaurant Table
INSERT INTO categoriesofrestaurant(nameCOR) VALUES('cateA');
INSERT INTO categoriesofrestaurant(nameCOR) VALUES('cateB');
INSERT INTO categoriesofrestaurant(nameCOR) VALUES('cateC');
INSERT INTO categoriesofrestaurant(nameCOR) VALUES('cateD');

-- RestaurantCategoryies Table
INSERT INTO restaurantcategories VALUES(1, 1);
INSERT INTO restaurantcategories VALUES(1, 2);
INSERT INTO restaurantcategories VALUES(1, 3);
INSERT INTO restaurantcategories VALUES(1, 4);

INSERT INTO restaurantcategories VALUES(2, 1);
INSERT INTO restaurantcategories VALUES(2, 2);
INSERT INTO restaurantcategories VALUES(2, 3);
INSERT INTO restaurantcategories VALUES(2, 4);

INSERT INTO restaurantcategories VALUES(3, 1);
INSERT INTO restaurantcategories VALUES(3, 2);
INSERT INTO restaurantcategories VALUES(3, 3);
INSERT INTO restaurantcategories VALUES(3, 4);

INSERT INTO restaurantcategories VALUES(4, 1);
INSERT INTO restaurantcategories VALUES(4, 2);
INSERT INTO restaurantcategories VALUES(4, 3);
INSERT INTO restaurantcategories VALUES(4, 4);

-- Food Table
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu A', 1, 1);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu B', 2, 2);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu C ', 3, 3);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu D', 4, 4);

-- Rate Table
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(4, 1, 1);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(5, 2, 1);

INSERT INTO rate(rateValue, restaurantID, userID) VALUES(1, 3, 2);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(2, 4, 2);


-- Booking Table
-- booking status: 0 is waiting, 1 is served, 2 is cancelled