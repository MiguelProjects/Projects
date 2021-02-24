drop table appuser cascade;

create table appuser (
	userid varchar(50) primary key,
	password varchar(50),
	gender varchar(50),
	birthday varchar(50),
	status varchar(50),
	citizenship varchar(50),
	guessGuess integer,
	guessWins integer,
	rpsWins integer,
	rpsLoses integer,
	rpsPlayed integer,
	frogsWins integer,
	frogsResets integer,
	frogsStucks integer
);

insert into appuser values('auser', '04f0cef3e4796f6967fd5bae6e2c9113','Male','2021-02-01','Professor', 'Canadian Citizen',1,2,3,4,5,6,7,8);

