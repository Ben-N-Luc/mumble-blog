SELECT
	*
FROM (
	SELECT
		tickets.id,
		tickets.subject,
		tickets.content,
		tickets.type,
		tickets.closed,
		tickets.date AS last_answer,
		COUNT(answers.id) AS answers,
		users.id AS user_id,
		users.pseudo,
		users.mail,
		users.rank
	FROM tickets
	LEFT JOIN answers ON answers.ticket_id = tickets.id
	INNER JOIN users ON users.id = tickets.user_id
	WHERE answers.date IS NULL
	GROUP BY tickets.id
	) AS tmp

UNION

SELECT
	*
FROM (
	SELECT
		tickets.id,
		tickets.subject,
		tickets.content,
		tickets.type,
		tickets.closed,
		MAX(answers.date) AS last_answer,
		COUNT(answers.id) AS answers,
		users.id AS user_id,
		users.pseudo,
		users.mail,
		users.rank
	FROM tickets
	LEFT JOIN answers ON answers.ticket_id = tickets.id
	INNER JOIN users ON users.id = tickets.user_id
	WHERE answers.date IS NOT NULL
	GROUP BY answers.ticket_id
	) AS tmp2

ORDER BY closed ASC, last_answer DESC
