<?php

class Ticket extends Model {

	public function liste($user_id = false) {
		if($user_id !== false) {
			$cond = ' WHERE ticket.user_id=' . $user_id . ' ';
		} else {
			$cond = '';
		}

		return $this->sql(
			'SELECT
				ticket.id AS ticket_id,
				ticket.subject AS ticket_subject,
				ticket.content AS ticket_content,
				ticket.type AS ticket_type,
				ticket.closed AS ticket_closed,
				answer.date AS ticket_date,
				COUNT(answer.id) AS answers,
				users.id AS user_id,
				users.pseudo AS user_pseudo,
				users.mail AS user_mail,
				users.rank AS user_rank
			FROM tickets AS ticket
			LEFT JOIN tickets AS answer ON(ticket.id = answer.master)
			INNER JOIN users ON(users.id = ticket.user_id)
			GROUP BY answer.master
			HAVING COUNT(answer.id) > 0
			' . $cond . '

			UNION

				SELECT
					ticket.id AS ticket_id,
					ticket.subject AS ticket_subject,
					ticket.content AS ticket_content,
					ticket.type AS ticket_type,
					ticket.closed AS ticket_closed,
					ticket.date AS ticket_date,
					COUNT(answer.id) AS answers,
					users.id AS user_id,
					users.pseudo AS user_pseudo,
					users.mail AS user_mail,
					users.rank AS user_rank
				FROM tickets AS ticket
				LEFT JOIN tickets AS answer ON(ticket.id = answer.master)
				INNER JOIN users ON(users.id = ticket.user_id)
				GROUP BY answer.master
				HAVING COUNT(answer.id) = 0
				' . $cond . '

			ORDER BY ticket_closed DESC, ticket_date DESC'
		)->fetchAll(PDO::FETCH_OBJ);
	}

}
