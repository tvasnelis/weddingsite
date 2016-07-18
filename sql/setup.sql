USE timvasne_wedding1;
DELETE FROM Guests WHERE GuestId = 1;
DELETE FROM Guests WHERE GuestId = 2;
DELETE FROM Guests WHERE GuestId = 3;
DELETE FROM Guests WHERE GuestId = 4;
DELETE FROM Guests WHERE GuestId = 5;
DELETE FROM Guests WHERE GuestId = 6;
DELETE FROM Guests WHERE GuestId = 7;
INSERT INTO Guests (GuestId, FirstName, LastName, GroupId, Guest_CatId, PlusOne) VALUES
(1, "John", "Doe", 1, 1, 0),
(2, "Jane", "Doe", 1, 1, 0),
(3, "Tom", "Smith", 2, 1, 0),
(4, NULL, NULL, 2, 1, 1),
(5, "John", "Smith", 3, 1, 0),
(6, "Tim", "Vasnelis", 4, 1, 0),
(7, "Kimberly", "Bean", 4, 1, 0);