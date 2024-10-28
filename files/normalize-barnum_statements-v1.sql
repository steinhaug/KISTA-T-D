Normalize the barnum_statements table, focusing on each row.


-- Step 1: Create a temporary table with unique statements
CREATE TEMPORARY TABLE temp_unique_statements AS
SELECT MIN(bsId) AS bsId, llmId, catId, score, gender, statement
FROM barnum_statements
GROUP BY statement, llmId, catId, score, gender;

-- Step 2: Delete all rows from the original table
DELETE FROM barnum_statements;

-- Step 3: Insert the unique rows back into the original table
INSERT INTO barnum_statements (bsId, llmId, catId, score, gender, statement)
SELECT * FROM temp_unique_statements;

-- Step 4: Drop the temporary table
DROP TEMPORARY TABLE temp_unique_statements;

-- Step 5: Reset the auto-increment value
ALTER TABLE barnum_statements AUTO_INCREMENT = 1;