-- Sample data used in Home index page 

INSERT INTO images(nameImage, addressImage) VALUES("profile", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-ao-quan-ngo-19-dong-tac-avatarjpg-5138.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("dish", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-chen-doan-tran-nghiep-lau-nuong-anh-vuong2-3511.jpg");
INSERT INTO images(nameImage, addressImage) VALUES("topPro", "http://cdn.pasgo.vn/anh-diem-den/nha-hang-nam-son-809-giai-phong-anh-vuong-4219.jpg");


INSERT INTO address(address, provinceID, districtID, wardID) VALUES("18 Ngõ 19 Đông Tác, Quận Đống Đa", '01', '001', '00001');

INSERT INTO users(firstnameUser, lastNameUser, imageID, addressID) VALUES("John", "Stunt", 1, 1);

INSERT INTO restaurants(nameRe, addressID, userID) VALUES("Mojo BBQ", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("Buffet Restaurant", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("A Rest", 1, 1);
INSERT INTO restaurants(nameRe, addressID, userID) VALUES("B Rest", 1, 1);

INSERT INTO rate(rateValue, restaurantID, userID) VALUES(4, 1, 1);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(5, 2, 1);

INSERT INTO rate(rateValue, restaurantID, userID) VALUES(5, 3, 1);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(2, 4, 1);

INSERT INTO food(nameFo,restaurantID, imageID) VALUES('Grilled Chicken', 1, 2);
INSERT INTO food(nameFo,restaurantID, imageID) VALUES('Food A', 2, 2);
INSERT INTO food(nameFo,restaurantID, imageID) VALUES('Food B ', 3, 2);
INSERT INTO food(nameFo,restaurantID, imageID) VALUES('Food B', 4, 2);