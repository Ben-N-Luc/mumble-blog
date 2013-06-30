<?php

class Ticket extends Model {

	public function liste(array $filtres = array()) {
		$cond = '';
		if(!empty($filtres)) {
			$pieces = array();
			foreach ($filtres as $k => $v) {
				$pieces[] = $k . '=' . $this->_quote($v);
			}
			$cond = 'WHERE ' . implode(' AND ', $pieces);
		}

		$sql = 'SELECT
				tickets.id,
				tickets.subject,
				tickets.content,
				tickets.type,
				tickets.closed,
				tickets.date,
				answers.date AS last_answer,
				COUNT(answers.id) AS answers,
				users.id AS user_id,
				users.pseudo,
				users.mail,
				users.rank
			FROM tickets
			LEFT JOIN answers ON answers.ticket_id = tickets.id
			INNER JOIN users ON users.id = tickets.user_id
			' . $cond . '
			GROUP BY tickets.id
			UNION
				SELECT
					tickets.id,
					tickets.subject,
					tickets.content,
					tickets.type,
					tickets.closed,
					tickets.date,
					answers.date AS last_answer,
					COUNT(answers.id) AS answers,
					users.id AS user_id,
					users.pseudo,
					users.mail,
					users.rank
				FROM tickets
				LEFT JOIN answers ON answers.ticket_id = tickets.id
				INNER JOIN users ON users.id = tickets.user_id
				' . $cond . '
				GROUP BY tickets.id
			ORDER BY closed ASC, date DESC';

		return $this->sql($sql)->fetchAll(PDO::FETCH_OBJ);
	}

	public function close($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 1));
	}

	public function open($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 0));
	}

}
