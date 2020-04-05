DELIMITER $$
CREATE DEFINER=`root`@`%` PROCEDURE `GetTransactionTypeName`(
    IN typeID INT,
    OUT typeName VARCHAR(35))
BEGIN
    SELECT
	name
    INTO
	typeName
    FROM
	transaction_types
    WHERE
	id = typeID;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`%` PROCEDURE `ProcessUserTransaction`(
    IN typeID INT,
    IN amount INT,
    IN sender_id INT,
    IN recipient_id INT)
BEGIN

	DECLARE transactionType VARCHAR(35);
	DECLARE balanceDifference INT DEFAULT 0;
	DECLARE userID INT DEFAULT 0;

	DECLARE InsufficientBalance CONDITION for 22003 ;
	DECLARE EXIT HANDLER FOR InsufficientBalance
    BEGIN
		SELECT 'The balance is not sufficient' AS message;
    END;


	CALL GetTransactionTypeName(typeID, transactionType);
	IF transactionType LIKE 'withdraw' THEN
		SET balanceDifference = amount * -1;
		SET userID = sender_id;
		CALL UpdateUserBalance(balanceDifference, userID);
	ELSEIF transactionType LIKE 'deposit' THEN
		SET balanceDifference = amount;
		SET userID = recipient_id;
		CALL UpdateUserBalance(balanceDifference, userID);
	ELSEIF transactionType LIKE 'transfer' THEN
		SET balanceDifference = amount * -1;
		SET userID = sender_id;
		CALL UpdateUserBalance(balanceDifference, userID);

		SET balanceDifference = amount;
		SET userID = recipient_id;
		CALL UpdateUserBalance(balanceDifference, userID);
	END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`%` PROCEDURE `UpdateUserBalance`(
    IN balanceDifference INT,
    IN userID INT)
BEGIN
	START TRANSACTION;
    UPDATE
        users
    SET
    	balance = balance + balanceDifference
    WHERE
    	id = userID;
	COMMIT;
END$$
DELIMITER ;
