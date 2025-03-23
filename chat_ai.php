<?php
// Include database connection
require_once 'Demo_DBConnection.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'error' => 'Invalid request method'
    ]);
    exit;
}

// Get the user message
$userMessage = isset($_POST['message']) ? trim($_POST['message']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : 'User';

if (empty($userMessage)) {
    echo json_encode([
        'status' => 'error',
        'error' => 'No message provided'
    ]);
    exit;
}

// Log the incoming message to database (optional)
$stmt = $conn->prepare("INSERT INTO ai_chat_logs (username, message, timestamp, is_ai) VALUES (?, ?, NOW(), 0)");
$stmt->bind_param("ss", $username, $userMessage);
$stmt->execute();
$stmt->close();

// Function to call OpenAI API
function callOpenAI($message)
{
    // Your OpenAI API key - in production, store this securely
    $apiKey = 'YOUR_OPENAI_API_KEY';

    // OpenAI API endpoint
    $url = 'https://api.openai.com/v1/chat/completions';

    // Prepare the request data
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful AI assistant for Lagro High School. Provide clear, concise, and accurate information. Be friendly and professional. Keep responses brief and appropriate for students.'
            ],
            [
                'role' => 'user',
                'content' => $message
            ]
        ],
        'max_tokens' => 300,
        'temperature' => 0.7
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for errors
    if (curl_errno($ch)) {
        return [
            'status' => 'error',
            'error' => 'cURL error: ' . curl_error($ch)
        ];
    }

    // Close cURL session
    curl_close($ch);

    // Process response
    if ($httpCode !== 200) {
        return [
            'status' => 'error',
            'error' => 'API error: HTTP code ' . $httpCode,
            'response' => $response
        ];
    }

    // Decode JSON response
    $responseData = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'status' => 'error',
            'error' => 'JSON decode error: ' . json_last_error_msg()
        ];
    }

    // Extract the assistant's message
    if (isset($responseData['choices'][0]['message']['content'])) {
        return [
            'status' => 'success',
            'response' => $responseData['choices'][0]['message']['content']
        ];
    } else {
        return [
            'status' => 'error',
            'error' => 'Unexpected API response format',
            'response' => $responseData
        ];
    }
}

// Check if the message is a math calculation
function handleMathCalculation($message)
{
    // Remove common words that might indicate a math problem
    $message = strtolower($message);
    $message = preg_replace('/what is|calculate|compute|solve|math|equals|equal to|result of/', '', $message);

    // Replace "x" with "*" for multiplication
    $message = str_replace(' x ', ' * ', $message);
    $message = str_replace('×', '*', $message); // Unicode multiplication symbol

    // Clean up the expression
    $expression = trim($message);
    $expression = preg_replace('/[^0-9+\-*\/().%\s]/', '', $expression);
    $expression = preg_replace('/\s+/', '', $expression);

    // Special handling for square root
    if (preg_match('/square\s*root\s*of\s*(\d+(\.\d+)?)/i', $message, $matches)) {
        $number = floatval($matches[1]);
        $result = sqrt($number);
        
        // Format the result based on whether it's an integer or float
        if (is_int($result) || $result == (int)$result) {
            return "The square root of {$number} is " . $result;
        } else {
            return "The square root of {$number} is " . number_format($result, 4, '.', '');
        }
    }

    // If we have a valid expression
    if (!empty($expression) && preg_match('/^[0-9+\-*\/().%]+$/', $expression)) {
        // Evaluate the expression safely
        try {
            // Replace % with * 0.01 for percentage calculations
            $expression = str_replace('%', '* 0.01', $expression);

            // Use eval() cautiously - only with thoroughly sanitized input
            $result = @eval("return $expression;");

            if ($result !== false && !is_null($result)) {
                // Format the result based on whether it's an integer or float
                if (is_int($result) || $result == (int)$result) {
                    // For integers, don't use number_format to avoid commas in large numbers
                    return "The answer is " . $result;
                } else {
                    // For decimals, format with 2 decimal places but no thousands separator
                    return "The answer is " . number_format($result, 2, '.', '');
                }
            }
        } catch (Throwable $e) {
            // If there's an error, we'll fall back to the regular response system
        }
    }

    return null;
}

// Function to get current time-based greeting
function getTimeBasedGreeting()
{
    $hour = date('G'); // 24-hour format without leading zeros

    if ($hour >= 5 && $hour < 12) {
        return "Good morning!";
    } elseif ($hour >= 12 && $hour < 18) {
        return "Good afternoon!";
    } else {
        return "Good evening!";
    }
}

// Improved function to get a simple response
function getSimpleResponse($message)
{
    // Convert to lowercase for case-insensitive matching
    $message = strtolower(trim($message));

    // Try to handle math calculations first
    $mathResponse = handleMathCalculation($message);
    if ($mathResponse !== null) {
        return $mathResponse;
    }

    // Special handling for very short messages (likely greetings)
    if (in_array($message, ['hi', 'hey', 'hello', 'yo', 'hola', 'greetings'])) {
        return getTimeBasedGreeting() . ' How can I help you today?';
    }

    // Define keyword categories and their responses
    $responses = [
        // Greetings - now handled above for exact matches, but keeping for phrases
        'greetings' => [
            'keywords' => ['hello there', 'hi there', 'hey there', 'good morning', 'good afternoon', 'good evening'],
            'response' => getTimeBasedGreeting() . ' How can I help you today?'
        ],

        // Well-being
        'well_being' => [
            'keywords' => ['how are you', 'how\'s it going', 'how are things', 'how do you do'],
            'response' => 'I\'m just a computer program, but I\'m functioning well! How can I assist you?'
        ],

        // Thanks
        'thanks' => [
            'keywords' => ['thank', 'thanks', 'appreciate', 'grateful'],
            'response' => 'You\'re welcome! Feel free to ask if you need anything else.'
        ],

        // Goodbye
        'goodbye' => [
            'keywords' => ['bye', 'goodbye', 'see you', 'farewell', 'later'],
            'response' => 'Goodbye! Have a great day!'
        ],

        // ID/Cards - Placing specific matches before general ones
        'id' => [
            'keywords' => ['school id', 'student id', 'identification card', 'id card', 'lost id', 'replace id'],
            'response' => 'School IDs must be worn at all times while on campus. If you lose your ID, report to the Student Affairs Office immediately. Replacement IDs cost ₱150 and take 3-5 working days to process. Temporary IDs can be issued while waiting for your replacement.'
        ],

        // School info - More general, so placed after specific matches
        'school_info' => [
            'keywords' => ['about school', 'school information', 'tell me about school', 'school details'],
            'response' => 'Lagro High School is committed to providing quality education and fostering a supportive learning environment for all students. The school offers comprehensive academic programs and various extracurricular activities to develop well-rounded individuals.'
        ],

        // Schedule
        'schedule' => [
            'keywords' => ['class schedule', 'school schedule', 'timetable', 'class periods', 'when is class'],
            'response' => 'Class schedules are available through the school portal. Regular classes run from 7:30 AM to 4:30 PM, Monday through Friday. If you need help accessing your schedule, please visit the registrar\'s office.'
        ],

        // Teachers
        'teachers' => [
            'keywords' => ['teacher', 'faculty', 'professor', 'instructor', 'staff'],
            'response' => 'Our faculty consists of dedicated professionals committed to student success. Each department has specialized teachers with expertise in their subject areas. You can find the faculty directory on the school website or visit the administration office for specific inquiries.'
        ],

        // Events
        'events' => [
            'keywords' => ['school event', 'school activity', 'program', 'celebration', 'festival', 'competition'],
            'response' => 'School events and activities are regularly posted on the bulletin boards and announced during morning assemblies. Major upcoming events include the Science Fair (October), Sports Festival (November), and the Annual Christmas Program (December). Check the school calendar for more details!'
        ],

        // Enrollment
        'enrollment' => [
            'keywords' => ['enroll', 'admission', 'register', 'application', 'requirements'],
            'response' => 'For enrollment and admission inquiries, please visit the Admissions Office or check the school website for requirements and procedures. The enrollment period for the next academic year typically begins in February. Required documents include birth certificate, report card from previous school, and proof of residency.'
        ],

        // Grades
        'grades' => [
            'keywords' => ['grade', 'score', 'mark', 'report card', 'evaluation', 'assessment', 'exam result'],
            'response' => 'Grades are accessible through the student portal. The grading system follows a 100-point scale with 75 as the passing mark. Report cards are distributed quarterly. If you have concerns about your grades, please speak with your subject teacher or guidance counselor.'
        ],

        // Uniform
        'uniform' => [
            'keywords' => ['school uniform', 'dress code', 'proper attire', 'clothing policy', 'what to wear'],
            'response' => 'Students are expected to wear the proper school uniform on regular days. This includes the prescribed polo/blouse with the school logo, pants/skirt in the designated color, black shoes, and the school ID. For the complete dress code policy, refer to the student handbook.'
        ],

        // Library
        'library' => [
            'keywords' => ['school library', 'borrow books', 'library hours', 'research materials', 'study area'],
            'response' => 'The school library is open from Monday to Friday, 7:30 AM to 4:30 PM. It houses a collection of textbooks, reference materials, fiction, and non-fiction books. Students can borrow books with their valid school ID for up to two weeks. The library also offers quiet study spaces and computer terminals for research.'
        ],

        // Sports
        'sports' => [
            'keywords' => ['school sports', 'sports team', 'athlete', 'basketball', 'volleyball', 'soccer', 'football', 'track', 'swimming'],
            'response' => 'Lagro High School offers various sports programs including basketball, volleyball, soccer, track and field, swimming, and badminton. Tryouts are announced at the beginning of each semester. The school participates in district and regional competitions throughout the academic year.'
        ],

        // Facilities
        'facilities' => [
            'keywords' => ['school facilities', 'building', 'classroom', 'laboratory', 'gym', 'canteen', 'cafeteria'],
            'response' => 'Our campus features modern classrooms, science laboratories, computer labs, a gymnasium, auditorium, cafeteria, and sports fields. We also have specialized rooms for music, arts, and technology education. All facilities are regularly maintained to provide an optimal learning environment.'
        ],

        // Clubs
        'clubs' => [
            'keywords' => ['school club', 'organization', 'society', 'student group', 'extracurricular'],
            'response' => 'The school offers various clubs and organizations including the Science Club, Math Club, Debate Society, Drama Club, Environmental Club, and Student Council. Joining these groups is a great way to develop skills and make friends. Club recruitment happens during the first month of each school year.'
        ],

        // Homework
        'homework' => [
            'keywords' => ['homework', 'assignment', 'project', 'school task', 'study assignment'],
            'response' => 'Homework assignments are given to reinforce classroom learning. Students should record all assignments in their planners. If you missed a class, you can check with classmates or directly contact your teacher. The school also has a homework assistance program available after regular class hours.'
        ],

        // Counseling
        'counseling' => [
            'keywords' => ['counseling', 'guidance', 'counselor', 'advice', 'problem', 'issue'],
            'response' => 'The Guidance and Counseling Office provides support for academic, personal, and career concerns. Counselors are available from 8:00 AM to 4:00 PM on weekdays. You can schedule an appointment or drop by during break times. All discussions are kept confidential.'
        ],

        // School Calendar
        'calendar' => [
            'keywords' => ['school calendar', 'holiday', 'vacation', 'school break', 'semester', 'term'],
            'response' => 'The school year is divided into four quarters. First semester runs from June to October, and second semester from November to March. Major breaks include Christmas vacation (December 20 to January 5), Semestral Break (last week of October), and Summer Break (April to May). Please check the official school calendar for specific dates of holidays and events.'
        ],

        // Technology
        'technology' => [
            'keywords' => ['school computer', 'laptop policy', 'internet access', 'wifi', 'technology lab', 'online learning', 'digital resources'],
            'response' => 'The school provides computer laboratories equipped with modern hardware and software. Wi-Fi is available in designated areas. Students can access the computer labs during free periods with proper permission. For technical assistance, please visit the IT Support Office located at the ground floor of the Main Building.'
        ],

        // Transportation
        'transportation' => [
            'keywords' => ['school transport', 'school bus', 'shuttle service', 'commute to school', 'parking policy'],
            'response' => 'The school offers shuttle services for students from major transportation hubs. Pickup points include the main terminal and shopping mall. Service runs from 6:00 AM to 7:00 AM (morning) and 4:30 PM to 5:30 PM (afternoon). For inquiries about routes and fees, please contact the Transportation Office.'
        ],

        // Scholarships
        'scholarships' => [
            'keywords' => ['scholarship', 'financial aid', 'grant', 'tuition discount', 'tuition fee'],
            'response' => 'Lagro High School offers academic scholarships for outstanding students and financial aid for deserving students with economic challenges. Applications for scholarships open every March for the following academic year. Requirements include academic records, recommendation letters, and family income documentation. Visit the Scholarship Office for more information.'
        ],

        // Health Services
        'health' => [
            'keywords' => ['school clinic', 'school nurse', 'school doctor', 'medical services', 'sick in school', 'injury'],
            'response' => 'The school clinic is open during school hours and staffed by a registered nurse. Basic first aid and health services are provided. For emergencies, the school has partnerships with nearby hospitals. All students are required to submit medical records and emergency contact information at the beginning of each school year.'
        ],

        // Canteen/Food
        'food' => [
            'keywords' => ['school food', 'canteen', 'cafeteria', 'lunch break', 'snack time', 'meal options', 'where to eat'],
            'response' => 'The school cafeteria offers a variety of nutritious meals and snacks at reasonable prices. It operates from 7:00 AM to 4:00 PM on school days. Students can also bring their own food. Microwaves are available for heating packed lunches. The cafeteria follows strict food safety standards and offers vegetarian options.'
        ],

        // Lost and Found
        'lost' => [
            'keywords' => ['lost item', 'found item', 'missing belongings', 'misplaced', 'lost and found'],
            'response' => 'The Lost and Found section is located at the Student Affairs Office. Found items are kept for one month before being donated. If you lost something, provide a detailed description of the item. Always label your belongings with your name and section to increase chances of recovery.'
        ],

        // Restrooms
        'restroom' => [
            'keywords' => ['restroom', 'bathroom', 'toilet', 'comfort room', 'cr'],
            'response' => 'Restrooms are located on each floor of every building. They are regularly cleaned and maintained. Please help keep them clean. Report any issues with the facilities to the maintenance staff or to your class adviser.'
        ],

        // School Rules
        'rules' => [
            'keywords' => ['school rules', 'school policy', 'regulation', 'guideline', 'prohibited items', 'allowed items', 'permission'],
            'response' => 'All school rules and policies are detailed in the Student Handbook provided at the beginning of the school year. Key policies include the attendance policy, code of conduct, anti-bullying policy, and academic integrity guidelines. Violations may result in disciplinary action as outlined in the handbook.'
        ],

        // Attendance
        'attendance' => [
            'keywords' => ['attendance policy', 'absent', 'late', 'tardy', 'excuse letter', 'attendance record'],
            'response' => 'Students are expected to maintain at least 85% attendance to qualify for final examinations. Absences should be explained with a written note from parents or a medical certificate. Tardiness is recorded and accumulated. Three instances of tardiness equal one absence. Notify your adviser in advance for planned absences.'
        ],

        // Exams
        'exams' => [
            'keywords' => ['exam schedule', 'test', 'quiz', 'assessment schedule', 'final exam', 'midterm exam'],
            'response' => 'Quarterly examinations are scheduled at the end of each quarter. The exam schedule is posted two weeks in advance. Make-up exams for excused absences must be arranged within one week of returning to school. Special exams may require additional fees. Review materials are usually provided by subject teachers one week before exams.'
        ],

        // Graduation
        'graduation' => [
            'keywords' => ['graduate', 'graduation', 'diploma', 'commencement', 'graduation ceremony'],
            'response' => 'Graduation ceremonies are held in March. Requirements include completing all academic requirements, settling financial obligations, and returning school property. Graduation practice usually begins two weeks before the ceremony. Graduation attire and fees information is distributed in January.'
        ],

        // Subjects
        'subjects' => [
            'keywords' => ['school subjects', 'course offerings', 'curriculum', 'math class', 'science class', 'english class', 'filipino class', 'history class'],
            'response' => 'The curriculum includes core subjects (Math, Science, English, Filipino, Social Studies), specialized subjects based on track/strand, and mandated subjects like Physical Education and Values Education. Each subject has specific requirements and grading components. Consult your class adviser or subject teacher for detailed information about specific subjects.'
        ],

        // Common Student Problems
        'problems' => [
            'keywords' => ['academic problem', 'learning difficulty', 'struggling with class', 'need help with', 'confused about', 'don\'t understand the lesson', 'having trouble with'],
            'response' => 'If you\'re having academic difficulties, speak with your subject teacher or guidance counselor. Peer tutoring and remedial classes are available. For personal issues, the guidance office provides confidential counseling. Remember that asking for help is a sign of strength, not weakness. The school community is here to support you.'
        ],

        // Time
        'time' => [
            'keywords' => ['current time', 'what time is it', 'time now', 'clock'],
            'response' => 'The current time is ' . date('g:i A') . '. School hours are from 7:30 AM to 4:30 PM, Monday through Friday.'
        ],

        // Date
        'date' => [
            'keywords' => ['today\'s date', 'what day is it', 'current day', 'what month', 'current year', 'what day of the week'],
            'response' => 'Today is ' . date('l, F j, Y') . '.'
        ],

        // Joke
        'joke' => [
            'keywords' => ['tell joke', 'say something funny', 'humor me', 'make me laugh'],
            'responses' => [
                'Why did the math book look sad? Because it had too many problems!',
                'What did the paper say to the pencil? Write on!',
                'Why did the student eat his homework? Because the teacher said it was a piece of cake!',
                'What do you call a bear with no teeth? A gummy bear!',
                'Why don\'t scientists trust atoms? Because they make up everything!',
                'What did one wall say to the other wall? I\'ll meet you at the corner!',
                'Why did the scarecrow win an award? Because he was outstanding in his field!',
                'How do you organize a space party? You planet!',
                'What do you call a fake noodle? An impasta!',
                'Why couldn\'t the bicycle stand up by itself? It was two tired!',
                'What do you call a dinosaur with an extensive vocabulary? A thesaurus!',
                'Why did the student bring a ladder to school? Because they wanted to go to high school!',
                'What\'s a computer\'s favorite snack? Microchips!',
                'Why was the math book depressed? It had too many problems.',
                'What do you call a parade of rabbits hopping backwards? A receding hare-line!',
                'What did the ocean say to the beach? Nothing, it just waved!',
                'Why don\'t eggs tell jokes? They might crack up!',
                'I told my wife she was drawing her eyebrows too high. She looked surprised!',
                'What do you call a fish wearing a crown? King of the sea!',
                'How does a penguin build its house? Igloos it together!'
            ]
        ],

        // Motivational quotes
        'motivation' => [
            'keywords' => ['motivate me', 'need inspiration', 'motivational quote', 'encourage me', 'feeling down'],
            'responses' => [
                'Education is the passport to the future, for tomorrow belongs to those who prepare for it today.',
                'The beautiful thing about learning is that no one can take it away from you.',
                'The only way to do great work is to love what you do.',
                'Believe you can and you\'re halfway there.',
                'Your attitude, not your aptitude, will determine your altitude.',
                'Success is not final, failure is not fatal: it is the courage to continue that counts.',
                'The future belongs to those who believe in the beauty of their dreams.',
                'Don\'t watch the clock; do what it does. Keep going.',
                'The expert in anything was once a beginner.',
                'You don\'t have to be great to start, but you have to start to be great.',
                'The more that you read, the more things you will know. The more that you learn, the more places you\'ll go.',
                'It always seems impossible until it\'s done.',
                'Education is not preparation for life; education is life itself.',
                'The only limit to our realization of tomorrow will be our doubts of today.',
                'Learning is a treasure that will follow its owner everywhere.'
            ]
        ],

        // Study tips
        'study_tips' => [
            'keywords' => ['how to study better', 'study advice', 'improve my grades', 'study habits', 'learning techniques'],
            'responses' => [
                'Create a dedicated study space free from distractions. Set a regular study schedule and stick to it. Break down large tasks into smaller, manageable chunks. Use active learning techniques like summarizing in your own words. Take short breaks every 25-30 minutes to maintain focus.',
                'Try the Pomodoro Technique: study for 25 minutes, then take a 5-minute break. After four cycles, take a longer 15-30 minute break. This helps maintain focus and prevents burnout. Also, teaching concepts to others is one of the best ways to solidify your understanding.',
                'Use mnemonic devices to remember complex information. Create mind maps to visualize connections between concepts. Review your notes within 24 hours of class to improve retention. Study difficult subjects when you\'re most alert. Join or form study groups to gain different perspectives.',
                'Practice retrieval by testing yourself instead of just re-reading notes. Use flashcards for key terms and concepts. Connect new information to things you already know. Get enough sleep - your brain consolidates memories during sleep. Stay hydrated and eat brain-healthy foods.',
                'Identify your learning style (visual, auditory, kinesthetic) and adapt your study methods accordingly. Use color-coding in your notes to organize information. Record yourself explaining difficult concepts and listen to the recordings. Set specific, achievable goals for each study session.'
            ]
        ],

        // Simple math problems
        'math_help' => [
            'keywords' => ['math help', 'math problem', 'solve equation', 'math formula', 'math calculation'],
            'response' => 'For math help, try breaking down the problem into smaller steps. Check your textbook for similar examples or formulas. The Math Club offers peer tutoring every Tuesday and Thursday after classes in Room 301. You can also use online resources like Khan Academy for additional explanations and practice problems.'
        ],

        // Who are you
        'identity' => [
            'keywords' => ['who are you', 'what are you', 'your name', 'chatbot', 'ai', 'assistant'],
            'response' => 'I\'m an AI assistant for Lagro High School. I\'m here to provide information about the school, answer questions about policies and procedures, and help with general inquiries. While I\'m not a human, I\'m designed to be helpful and informative!'
        ],

        // Help
        'help' => [
            'keywords' => ['how can you help', 'what can you assist with', 'what support', 'how to use this', 'what can you do'],
            'response' => 'I can help with information about Lagro High School including schedules, policies, events, facilities, and academic programs. I can also provide general assistance with common student questions, simple calculations, and offer study tips. Just ask me what you need help with!'
        ],

        // General school-related keywords (placed at the end to prioritize specific matches)
        'general_school' => [
            'keywords' => ['school', 'lagro', 'high school', 'education', 'learning', 'student', 'campus'],
            'response' => 'Lagro High School is committed to providing quality education in a supportive environment. Our mission is to develop well-rounded individuals prepared for future academic and career success. If you have specific questions about programs, facilities, or policies, please let me know!'
        ]
    ];

    // First, try to find exact phrase matches (more specific)
    foreach ($responses as $category => $data) {
        if ($category === 'joke' || $category === 'motivation' || $category === 'study_tips') {
            // Skip these for now, we'll handle them in the next loop
            continue;
        }

        foreach ($data['keywords'] as $keyword) {
            // Check for exact phrase matches
            if (
                $message === $keyword || strpos($message, $keyword . ' ') === 0 || strpos($message, ' ' . $keyword . ' ') !== false ||
                strpos($message, ' ' . $keyword) === strlen($message) - strlen(' ' . $keyword)
            ) {
                return $data['response'];
            }
        }
    }

    // Sort keywords by length (descending) to prioritize longer, more specific phrases
    $matchedResponses = [];
    foreach ($responses as $category => $data) {
        if ($category === 'joke' && containsAnyKeyword($message, $data['keywords'])) {
            // For jokes, return a random response from the array
            return $data['responses'][array_rand($data['responses'])];
        } else if ($category === 'motivation' && containsAnyKeyword($message, $data['keywords'])) {
            // For motivational quotes, return a random response from the array
            return $data['responses'][array_rand($data['responses'])];
        } else if ($category === 'study_tips' && containsAnyKeyword($message, $data['keywords'])) {
            // For study tips, return a random response from the array
            return $data['responses'][array_rand($data['responses'])];
        } else if (containsAnyKeyword($message, $data['keywords'])) {
            // Store the match and the length of the longest matching keyword
            $maxLength = 0;
            foreach ($data['keywords'] as $keyword) {
                if (strpos($message, $keyword) !== false && strlen($keyword) > $maxLength) {
                    $maxLength = strlen($keyword);
                }
            }

            $matchedResponses[] = [
                'category' => $category,
                'response' => $data['response'],
                'maxKeywordLength' => $maxLength
            ];
        }
    }

    // If we have matches, sort by keyword length (descending) and return the most specific match
    if (!empty($matchedResponses)) {
        usort($matchedResponses, function ($a, $b) {
            return $b['maxKeywordLength'] - $a['maxKeywordLength'];
        });

        return $matchedResponses[0]['response'];
    }

    // Check for common questions that might not be covered by keywords
    if (preg_match('/^(what|when|where|who|how|why|is|are|can|do|does)/i', $message)) {
        // This is likely a question, provide a more helpful default response
        return 'That\'s a good question about "' . substr($message, 0, 30) . (strlen($message) > 30 ? '...' : '') . '". While I don\'t have specific information on this, I recommend checking with your teacher, class adviser, or the school administration office for the most accurate answer. Is there something else I can help you with?';
    }

    // Default response if no keywords match
    return 'I understand you\'re asking about "' . substr($message, 0, 30) . (strlen($message) > 30 ? '...' : '') . '". As an AI assistant for Lagro High School, I can help with questions about academics, school policies, events, and student life. Could you please provide more details or rephrase your question?';
}

// Helper function to check if a message contains any of the keywords
function containsAnyKeyword($message, $keywords)
{
    foreach ($keywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

// Get AI response
// Uncomment the line below to use OpenAI API (replace with your API key in the function)
// $aiResponse = callOpenAI($userMessage);
// $responseText = $aiResponse['status'] === 'success' ? $aiResponse['response'] : 'Sorry, I encountered an error. Please try again.';

// For demonstration, use the improved simple response function
$responseText = getSimpleResponse($userMessage);



// Add a small delay to simulate processing time (optional)
usleep(500000); // 0.5 seconds

// Return the response
echo json_encode([
    'status' => 'success',
    'message' => $responseText,
    'sender' => 'AI Assistant',
    'time' => date('n/j/Y, g:i A')
]);

