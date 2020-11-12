CREATE TABLE `todos` (
  `id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `work_name` varchar(255) NOT NULL,
  `status` varchar(50)  CHECK ((status = 'Planning' OR status='Doing' OR status = 'Complete'))
)
