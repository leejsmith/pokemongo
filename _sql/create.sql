CREATE TABLE tbl_Types (
    typeID INT AUTO_INCREMENT NOT NULL,
    typeName VARCHAR(128) NOT NULL,
    PRIMARY KEY (typeID)
);

CREATE TABLE tbl_Pokemon (
    pokemonID INT AUTO_INCREMENT NOT NULL,
    pokemonName VARCHAR(128) NOT NULL,
    pokemonType1 INT NOT NULL,
    pokemonType2 INT,
    PRIMARY KEY (pokemonID),
);

CREATE TABLE tbl_FastMoves (
	moveID INT AUTO_INCREMENT NOT NULL,
	moveName VARCHAR(128),
	moveTypeID INT NOT NULL,
	movePower INT,
	moveEnergy INT,
	moveDPS DECIMAL,
	moveEPS DECIMAL,
	moveTime DECIMAL,
	PRIMARY KEY (moveID)
);

CREATE TABLE tbl_ChargedMoves (
	moveID INT AUTO_INCREMENT NOT NULL,
	moveName VARCHAR(128),
	moveTypeID INT NOT NULL,
	moveEnergyBar INT NOT NULL,
	movePower INT,
	moveDuration DECIMAL,
	moveActive DECIMAL,
	PRIMARY KEY (moveID)
);

CREATE TABLE tbl_PokemonToFast (
	pokemonID INT NOT NULL,
	moveID INT NOT NULL,
	PRIMARY KEY (pokemonID, moveID),
	FOREIGN KEY (pokemonID) REFERENCES tbl_Pokemon(pokemonID),
	FOREIGN KEY (moveID) REFERENCES tbl_FastMoves(moveID)
);

CREATE TABLE tbl_PokemonToCharged (
	pokemonID INT NOT NULL,
	moveID INT NOT NULL,
	PRIMARY KEY (pokemonID, moveID),
	FOREIGN KEY (pokemonID) REFERENCES tbl_Pokemon(pokemonID),
	FOREIGN KEY (moveID) REFERENCES tbl_ChargedMoves(moveID)
);

CREATE TABLE tbl_Items (
	ItemID INT AUTO_INCREMENT NOT NULL,
	itemName VARCHAR(128) NOT NULL,
	itemDesc TEXT,
	itemDropPC DECIMAL(10,2),
	PRIMARY KEY (itemID)
);

CREATE TABLE tbl_Evolution (
	pokemon1 INT NOT NULL,
	pokemon2 INT NOT NULL,
	item INT,
	description TEXT,
	PRIMARY KEY (pokemon1,pokemon2),
	FOREIGN KEY (pokemon1) REFERENCES tbl_Pokemon(pokemonID),
	FOREIGN KEY (pokemon2) REFERENCES tbl_Pokemon(pokemonID)
);

CREATE TABLE tbl_Users (
	userID INT AUTO_INCREMENT NOT NULL,
	username VARCHAR(1024) NOT NULL,
	salt VARCHAR(32) NOT NULL,
	password VARCHAR(256) NOT NULL,
	email VARCHAR(1028) NOT NULL,
	PRIMARY KEY (userID)
);

CREATE TABLE tbl_UserPokemon (
	pokemonUserID INT AUTO_INCREMENT NOT NULL,
	userID INT NOT NULL,
	pokemonID INT NOT NULL,
	candy INT NOT NULL,
	cp INT NOT NULL,
	fastMove INT NOT NULL,
	chargedMove INT NOT NULL,
	PRIMARY KEY (pokemonUserID),
	FOREIGN KEY (userID) REFERENCES tbl_Users(userID),
	FOREIGN KEY (pokemonID) REFERENCES tbl_Pokemon(pokemonID),
	FOREIGN KEY (fastMove) REFERENCES tbl_FastMoves(moveID),
	FOREIGN KEY (chargedMove) REFERENCES tbl_ChargedMoves(moveID)
);