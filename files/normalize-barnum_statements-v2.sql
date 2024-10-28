Normalize the barnum_statements table, only focusing on the statement column.


-- Step 1: Create a temporary table with unique statements
CREATE TEMPORARY TABLE temp_unique_statements AS
SELECT MIN(bsId) AS bsId
FROM barnum_statements
GROUP BY statement;

-- Step 2: Delete all rows from the original table that are not in the temporary table
DELETE bs FROM barnum_statements bs
LEFT JOIN temp_unique_statements tus ON bs.bsId = tus.bsId
WHERE tus.bsId IS NULL;

-- Step 3: Drop the temporary table
DROP TEMPORARY TABLE temp_unique_statements;

-- Optional Step 4: Optimize the table and reset auto-increment
OPTIMIZE TABLE barnum_statements;
ALTER TABLE barnum_statements AUTO_INCREMENT = 1;
