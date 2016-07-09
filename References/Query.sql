-- Sample data used in Home index page 

<<<<<<< HEAD
INSERT INTO images(nameImage, addressImage) VALUES("profile", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-ao-quan-ngo-19-dong-tac-avatarjpg-5138.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("dish", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-chen-doan-tran-nghiep-lau-nuong-anh-vuong2-3511.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("topPro", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-nam-son-809-giai-phong-anh-vuong-4219.jpg");


INSERT INTO address(address, provinceID, districtID, wardID) VALUES('', '', '', '');

INSERT INTO users(firstnameUser, lastNameUser, imageID, addressID) VALUES("John", "Stunt", 1, null);

INSERT INTO restaurants(nameRe, addressID, userID) VALUES("Mojo BBQ", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("Buffet Restaurant", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("A Rest", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("B Rest", 1, 1);

=======
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
INSERT INTO users(firstnameUser, imageID, addressID) VALUES("John", 5, 1);
INSERT INTO users(firstnameUser, imageID, addressID) VALUES("Kelly", 6, 2);


-- Restaurants Table
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest A", "Should be Campaign A", 40, 1, 1);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest B", "Should be Campaign B", 30, 2, 2);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest C", "Should be Campaign C", 40, 3, 1);
INSERT INTO restaurants(nameRe, descriptionRes, discount, addressID, userID) VALUES("Rest D", "Should be Campaign D", 20, 4, 2);

-- Food Table
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu A', 1, 1);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu B', 2, 2);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu C ', 3, 3);
INSERT INTO food(nameFo, restaurantID, imageID) VALUES('Menu D', 4, 4);

-- Rate Table
>>>>>>> 7a552301b9e4e526817ebb43c9dda64ea95fee79
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(4, 1, 1);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(5, 2, 1);

INSERT INTO rate(rateValue, restaurantID, userID) VALUES(1, 3, 2);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(2, 4, 2);
