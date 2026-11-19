-- Import this after animalbd.sql. It enables automatic IDs for new adoption requests.
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
