-- Seed data for local development and demonstration only.
-- This file should not be used for production data.

USE life_network_campus;

INSERT INTO users (name, email, password_hash, university, field_of_study, year_of_study, bio, interests, skills, career_interests)
VALUES (
    'Demo Student',
    'demo.student@life-network-campus.test',
    '$2y$10$s64dh9Q6PLmOE44gS4SGWeVSlIGwJkJ91x7MHxvP/xSOHhhjmSd8W',
    'University of Technology',
    'Computer Science',
    2,
    'I am building a portfolio of learning, project work, and community contribution.',
    'AI, community volunteering, design, learning',
    'Research, teamwork, Python, SQL',
    'Software engineering, data science, mentorship'
) ON DUPLICATE KEY UPDATE email = email;

INSERT INTO communities (creator_id, name, description, category)
VALUES
    (1, 'AI for Good Lab', 'Students exploring applied AI for community problems.', 'Technology'),
    (1, 'Campus Makers', 'A place for creative technology and entrepreneurial projects.', 'Entrepreneurship')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO opportunities (created_by, title, description, category, opportunity_type, status, deadline, skills_required, organization_name, application_instructions)
VALUES
    (
        1,
        'AI Community Volunteer Project',
        'Help students build a small AI-based community solution for campus improvement.',
        'Community',
        'Research',
        'Open',
        DATE_ADD(CURRENT_DATE, INTERVAL 21 DAY),
        'Python, teamwork, research, communication',
        'Life Network Campus Demo Org',
        'Send a short message explaining your interest and relevant skills.'
    ),
    (
        1,
        'Student Design Sprint Mentor',
        'Work with a small team building a user-centered product for a social challenge.',
        'Leadership',
        'Mentorship',
        'Open',
        DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY),
        'UX thinking, facilitation, design, teamwork',
        'Life Network Campus Demo Org',
        'Express interest with a short profile and availability.'
    )
ON DUPLICATE KEY UPDATE title = title;

INSERT INTO portfolio_items (user_id, title, description, item_type, visibility)
VALUES
    (1, 'Campus AI prototype', 'Built a small prototype that helps students find relevant campus resources using structured search.', 'Project', 'public'),
    (1, 'Peer research presentation', 'Presented findings from a student research sprint on improving peer learning and community participation.', 'Research', 'public')
ON DUPLICATE KEY UPDATE title = title;

INSERT INTO user_goals (user_id, title, description, category, status, target_date)
VALUES (
    1,
    'Learn AI and help my community',
    'Build skills in machine learning and use them in a useful campus project.',
    'Learning',
    'In Progress',
    DATE_ADD(CURRENT_DATE, INTERVAL 90 DAY)
) ON DUPLICATE KEY UPDATE title = title;

INSERT INTO goal_steps (goal_id, title, is_completed)
VALUES
    (1, 'Identify the AI skills required for the project', 0),
    (1, 'Find relevant people and communities', 1),
    (1, 'Create a small project prototype', 0)
ON DUPLICATE KEY UPDATE title = title;
