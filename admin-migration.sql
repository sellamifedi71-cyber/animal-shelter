-- Import once after migration.sql to enable the staff-only adoption review dashboard.
ALTER TABLE `utilisateur`
  ADD COLUMN `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user';
