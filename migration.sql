-- Import this after animalbd.sql. It enables automatic IDs for new adoption requests.
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- Prevent duplicate pending requests even if two submissions arrive simultaneously.
-- Existing sample data has no duplicate pending (user, animal) pairs.
ALTER TABLE `demande`
  ADD COLUMN `pending_idu` int(11) GENERATED ALWAYS AS (CASE WHEN `decision` = 'W' THEN `idu` ELSE NULL END) STORED,
  ADD UNIQUE KEY `uq_demande_pending_user_animal` (`pending_idu`, `ida`);
