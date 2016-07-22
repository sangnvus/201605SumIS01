-- typeImage: 0 avatar, 1 restaurant image, 2 banner, 3 food
-- authorityUser: 1 customer, 2 restaurant owner


INSERT INTO address(address, provinceID, districtID, wardID) VALUES("18 Ngõ A Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("20 Ngõ B Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("32 Ngõ C Đông Tác, Quận Đống Đa", '01', '001', '00001');
INSERT INTO address(address, provinceID, districtID, wardID) VALUES("42 Ngõ D Đông Tác, Quận Đống Đa", '01', '001', '00001');

INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('A', 2, 1);
INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('B', 2, 1);
INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('C', 2, 1);
INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('D', 1, 2);
INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('E', 2, 1);
INSERT INTO users(firstNameUser, authorityUser, addressID) VALUES('F', 2, 3);

INSERT INTO images(nameImage, typeImage, addressImage, userID) VALUES("A", 2, "http://economictimes.indiatimes.com/thumb/msid-50941625,width-640,resizemode-4/mughal-gardens-in-full-bloom.jpg", 1);
INSERT INTO images(nameImage, typeImage, addressImage, userID) VALUES("A", 2, "http://www.gettyimages.co.uk/gi-resources/images/Homepage/Hero/US/embed-504165888.jpg", 2);
INSERT INTO images(nameImage, typeImage, addressImage, userID) VALUES("A", 2, "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTwHCSZ6IF0BqH8ET0DjaSn9ORbY59Zi-NxlLljfKiRV0Ycv1Bj5A", 3);
INSERT INTO images(nameImage, typeImage, addressImage, userID) VALUES("A", 2, "http://www.socialmediaexaminer.com/wp-content/uploads/2016/02/kh-social-media-images-600.png", 1);
INSERT INTO images(nameImage, typeImage, addressImage, userID) VALUES("A", 2, "https://1.bp.blogspot.com/-pJRvVnLqgt0/VsWYK0K2mRI/AAAAAAAAAFE/W3GPxou4bv8/s1600/holi%2Bimages%2B2.jpg", 2);

INSERT INTO restaurants(nameRe, campaign, discount, addressID, userID) VALUES("Rest A", "Campaign A", 40, 1, 1);
INSERT INTO restaurants(nameRe, campaign, discount, addressID, userID) VALUES("Rest B", "Campaign B", 60, 2, 2);
INSERT INTO restaurants(nameRe, campaign, discount, addressID, userID) VALUES("Rest C", "Campaign C", 20, 3, 3);
INSERT INTO restaurants(nameRe, campaign, discount, addressID, userID) VALUES("Rest D", "Campaign D", 10, 4, 5);
INSERT INTO restaurants(nameRe, campaign, discount, addressID, userID) VALUES("Rest E", "Campaign E", 10, 3, 6);

INSERT INTO rate(rateValue, restaurantID, userID) VALUES(4, 1, 1);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(5, 2, 2);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(1, 3, 3);
INSERT INTO rate(rateValue, restaurantID, userID) VALUES(2, 4, 4);
