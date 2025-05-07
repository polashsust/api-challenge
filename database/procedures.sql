-- Procedure: GetServerTime
-- This procedure retrieves the current server time.
-- It returns the server time in a single column named `server_time`.

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetServerTime`()
BEGIN
    SELECT NOW() AS server_time;
END$$
DELIMITER ;

-- Procedure: get_flagbits_for_transaction
-- This procedure retrieves flag bits for a given transaction ID.
-- It joins the `stamd_flagbit_ref` table with `vorgaben_zeitraum` and `vorgaben_flagbit` to get the flag bit details.

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_flagbits_for_transaction`(
    IN IN_trans_id BIGINT
)
BEGIN
    SELECT 
        sfr.flagbit,
        vf.beschreibung AS flagbit_description
    FROM 
        stamd_flagbit_ref sfr
    INNER JOIN vorgaben_zeitraum vz ON vz.zeitraum_id = sfr.zeitraum_id
    INNER JOIN vorgaben_flagbit vf ON vf.flagbit_id = sfr.flagbit
    WHERE 
        sfr.datensatz_typ_id = 2
        AND sfr.datensatz_id = IN_trans_id
        AND NOW() BETWEEN vz.von AND vz.bis;
END$$
DELIMITER ;

-- Procedure: validate_apikey
-- This procedure validates an API key and retrieves associated details.

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validate_apikey`(
    IN IN_apikey VARCHAR(255)
)
BEGIN
    SELECT
        api_apikey.vertrag_id,
        api_apikey.bearbeiter_id,
        api_apikey.ist_masterkey
    FROM
        api_apikey
    INNER JOIN vorgaben_zeitraum
        ON vorgaben_zeitraum.zeitraum_id = api_apikey.zeitraum_id
    WHERE
        api_apikey.apikey = IN_apikey
        AND NOW() BETWEEN vorgaben_zeitraum.von AND vorgaben_zeitraum.bis
    LIMIT 1;
END$$
DELIMITER ;
