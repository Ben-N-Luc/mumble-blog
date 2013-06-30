<?php

class Ticket extends Model {

	public function liste(array $filtres = array()) {
		$pieces = array();
		if(!empty($filtres)) {
			foreach ($filtres as $k => $v) {
				$pieces[] = $k . '=' . $this->_quote($v);
			}
		}
		$cond1 = 'WHERE ' . implode(' AND ', array_merge($pieces, array('answers.date IS NULL')));
		$cond2 = 'WHERE ' . implode(' AND ', array_merge($pieces, array('answers.date IS NOT NULL')));

		$sql = '
			SELECT
				*
			FROM (
				SELECT
					tickets.id,                   tickets.subject,
					tickets.content,              tickets.type,
					tickets.closed,               tickets.date AS last_answer,
					COUNT(answers.id) AS answers, users.id AS user_id,
					users.pseudo,                 users.mail,
					users.rank
				FROM tickets
				LEFT JOIN answers ON answers.ticket_id = tickets.id
				INNER JOIN users ON users.id = tickets.user_id
				' . $cond1 . '
				GROUP BY tickets.id
				) AS tmp

			UNION

			SELECT
				*
			FROM (
				SELECT
					tickets.id,                   tickets.subject,
					tickets.content,              tickets.type,
					tickets.closed,               MAX(answers.date) AS last_answer,
					COUNT(answers.id) AS answers, users.id AS user_id,
					users.pseudo,                 users.mail,
					users.rank
				FROM tickets
				LEFT JOIN answers ON answers.ticket_id = tickets.id
				INNER JOIN users ON users.id = tickets.user_id
				' . $cond2 . '
				GROUP BY answers.ticket_id
				) AS tmp2

			ORDER BY closed ASC, last_answer DESC';

		return $this->sql($sql)->fetchAll(PDO::FETCH_OBJ);
	}

	public function close($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 1));
	}

	public function open($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 0));
	}

}
