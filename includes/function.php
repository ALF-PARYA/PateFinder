<?php
//ATTENTION ! IT'S A DELETE QUERY !
function deleteChar($tableName, $charaID, $connection)
{
    $queryDelete = "DELETE FROM $tableName WHERE character_id = :charact_id ";
    $statementDeleteCharacter = $connection->prepare($queryDelete);
    $statementDeleteCharacter->bindValue(':charact_id', $charaID, PDO::PARAM_INT);
    $statementDeleteCharacter->execute();
}

//SELECT FROM Played_Character
function selectCharacter(int $characId, PDO $connection): Character
{
    $characterStatement = "SELECT * FROM `played_character` WHERE `character_id` = :character_id;";
    $queryCharacter = $connection->prepare($characterStatement);
    $queryCharacter->bindValue(':character_id', $characId, PDO::PARAM_INT);
    $queryCharacter->execute();
    //Creation of an object $character based on Character class
    $queryCharacter->setFetchMode(PDO::FETCH_CLASS, Character::class);
    $character = $queryCharacter->fetch();
    return $character;
}

//SELECT FROM Statistic
function selectStatistic(int $characId , PDO $connection): array
{
    //SELECT FROM Character_Statistic
    $selectStat = "SELECT * FROM character_statistic WHERE character_id= :charact_id";
    $queryStat = $connection->prepare($selectStat);
    $queryStat->bindValue(':charact_id', $characId, PDO::PARAM_INT);
    $queryStat->setFetchMode(PDO::FETCH_CLASS, CharacterStatistic::class);
    $queryStat->execute();
    $characStat = $queryStat->fetchAll();

    $characStatById = [] ; 
    foreach($characStat as $key => $characterStatistic ){
    $characStatById [$characterStatistic->getStatistic_id()] = $characterStatistic ;
    }
    return $characStatById;
}

//SELECT CHARACTERID AND PLAYERID TO CHECK IF THEY MATCH 
function combinationCheck(PDO $connection, int $characterId, int $userId)
{
    $sqlVerif = "SELECT * FROM played_character WHERE character_id = :characterId AND player_id = :playerId";
    $statementcombination = $connection->prepare($sqlVerif);
    $statementcombination->bindValue(':characterId', $characterId, PDO::PARAM_INT);
    $statementcombination->bindValue(':playerId', $userId, PDO::PARAM_INT);
    $statementcombination->execute();
    $resultVerif = $statementcombination->fetch();

    if (empty($resultVerif)) {
        header('Location: ?page=characters_list.php');
    }
}