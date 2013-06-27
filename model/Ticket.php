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
				ticket.id,
				ticket.subject,
				ticket.content,
				ticket.type,
				ticket.closed,
				answer.date,
				COUNT(answer.id) AS answers,
				users.id AS user_id,
				users.pseudo,
				users.mail,
				users.rank
			FROM tickets AS ticket
			LEFT JOIN tickets AS answer ON(ticket.id = answer.master)
			INNER JOIN users ON(users.id = ticket.user_id)
			GROUP BY answer.master
			HAVING COUNT(answer.id) > 0
			' . $cond;
		$sql .= ' UNION ';
		$sql .= 'SELECT
				ticket.id,
				ticket.subject,
				ticket.content,
				ticket.type,
				ticket.closed,
				ticket.date,
				0 AS answers,
				users.id AS user_id,
				users.pseudo,
				users.mail,
				users.rank
			FROM tickets AS ticket
			LEFT JOIN tickets AS answer ON(ticket.id = answer.master)
			INNER JOIN users ON(users.id = ticket.user_id)
			GROUP BY ticket.id, answer.master
			HAVING COUNT(answer.id) = 0
			' . $cond;
		$sql .= ' ORDER BY closed ASC, date DESC';

		return $this->sql($sql)->fetchAll(PDO::FETCH_OBJ);
	}

	public function close($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 1));
	}

	public function open($ticket_id) {
		$this->update(array('id' => $ticket_id), array('closed' => 0));
	}

}
