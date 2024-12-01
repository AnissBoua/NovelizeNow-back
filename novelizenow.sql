-- Active: 1629727277044@@127.0.0.1@3306@novelizenow
INSERT INTO user VALUES 
(1, 'helene@domain.com', '["ROLE_USER"]', 'password', 'Helene', 'Tursten', 100, 'helene', NULL),
(2, 'elly@domain.com', '["ROLE_USER"]', 'password', 'Joel', 'Elly', 100, 'joel', NULL);

INSERT INTO category VALUES
(1, "SCI-FI",NULL),
(2, "Fantastic",NULL),
(3, "Romance",NULL),
(4, "Mystery",NULL),
(5, "Humourous",NULL),
(6, "Horror",NULL),
(7, "Action",NULL),
(8, "Adventure",NULL),
(9, "Thriller",NULL),
(10, "Drama",NULL),
(12, "Comedy",NULL),
(13, "Animation",NULL),
(14, "Family",NULL),
(15, "Crime",NULL),
(16, "Historical",NULL),
(17, "Fantasy",NULL),
(18, "Documentary",NULL),
(19, "War",NULL),
(20, "Musical",NULL),
(21, "Biography",NULL);

INSERT INTO novel VALUES
(1, "Detective Inspector Huss", "One of the most prominent citizens of Göteborg, Sweden, plunges to his death off an apartment balcony, but what appears to be a “society suicide” soon reveals itself to be a carefully plotted murder. Irene Huss finds herself embroiled in a complex and high-stakes investigation. As Huss and her team begin to uncover the victim's hidden past, they are dragged into Sweden's seamy underworld of street gangs, struggling immigrants, and neo-Nazis in order to catch the killer.", "detective-inspector-huss", 29, 'published', NOW(), NULL),
(2, "The Last of Us: A Post-Apocalyptic Journey", "In a world ravaged by a deadly fungal infection, survivors Joel and Ellie embark on a perilous cross-country journey to find a cure.", "the-last-of-us-a-post-apocalyptic-journey", 29, 'published', NOW(), NULL);


INSERT INTO user_novel VALUES
(1, 1, 1, "author"),(2, 2, 2, "author");

INSERT INTO chapter VALUES
(1, 1, "chapter1", "Published", "a:0:{}"),(2, 1, "chapter2", "Published", "a:0:{}"),(3, 2, "chapter1", "Published", "a:0:{}"),(4, 2, "chapter2", "Published", "a:0:{}");

INSERT INTO page VALUES
(1,2,"La confiance en soi est une qualité essentielle pour réussir dans la vie. Elle est la clé qui ouvre les portes de l'opportunité et vous permet de surmonter les obstacles qui se dressent sur votre chemin. La confiance en soi vous aide à croire en votre potentiel et à vous concentrer sur vos objectifs, plutôt que de vous laisser submerger par les doutes et les craintes. Les personnes qui ont confiance en elles ont tendance à être plus optimistes et résilientes face aux difficultés. Elles sont plus à l'aise dans leur peau et ont une meilleure estime d'elles-mêmes, ce qui se reflète dans leurs relations avec les autres. En outre, elles sont plus susceptibles de prendre des risques et d'essayer de nouvelles choses, ce qui peut les aider à découvrir de nouveaux talents et à acquérir de nouvelles compétences. Cependant, la confiance en soi ne vient pas naturellement pour tout le monde. Certaines personnes peuvent lutter contre l'anxiété sociale, le perfectionnisme ou le manque d'estime de soi, qui peuvent toutes nuire à leur confiance en soi. Heureusement, il existe des stratégies que vous pouvez utiliser pour renforcer votre confiance en vous. Une façon de renforcer votre confiance en vous est de travailler sur votre estime de soi. Cela peut inclure la pratique de l'auto-compassion, l'identification de vos forces et de vos faiblesses et la célébration de vos réussites, même les plus petites. Une autre façon de renforcer votre confiance en vous est de vous fixer des objectifs réalisables et de travailler dur pour les atteindre. Chaque fois que vous atteignez un objectif, vous renforcez votre confiance en vous-même et vous vous sentez plus compétent.", "<p><span>La confiance en soi est une qualité essentielle pour réussir dans la vie. Elle est la clé qui ouvre les portes de l'opportunité et vous permet de surmonter les obstacles qui se dressent sur votre chemin. La confiance en soi vous aide à croire en votre potentiel et à vous concentrer sur vos objectifs, plutôt que de vous laisser submerger par les doutes et les craintes. Les personnes qui ont confiance en elles ont tendance à être plus optimistes et résilientes face aux difficultés. Elles sont plus à l'aise dans leur peau et ont une meilleure estime d'elles-mêmes, ce qui se reflète dans leurs relations avec les autres. En outre, elles sont plus susceptibles de prendre des risques et d'essayer de nouvelles choses, ce qui peut les aider à découvrir de nouveaux talents et à acquérir de nouvelles compétences. Cependant, la confiance en soi ne vient pas naturellement pour tout le monde. Certaines personnes peuvent lutter contre l'anxiété sociale, le perfectionnisme ou le manque d'estime de soi, qui peuvent toutes nuire à leur confiance en soi. Heureusement, il existe des stratégies que vous pouvez utiliser pour renforcer votre confiance en vous. Une façon de renforcer votre confiance en vous est de travailler sur votre estime de soi. Cela peut inclure la pratique de l'auto-compassion, l'identification de vos forces et de vos faiblesses et la célébration de vos réussites, même les plus petites. Une autre façon de renforcer votre confiance en vous est de vous fixer des objectifs réalisables et de travailler dur pour les atteindre. Chaque fois que vous atteignez un objectif, vous renforcez votre confiance en vous-même et vous vous sentez plus compétent. </span></p>"),
(2,2,"La confiance en soi est une qualité essentielle pour réussir dans la vie. Elle est la clé qui ouvre les portes de l'opportunité et vous permet de surmonter les obstacles qui se dressent sur votre chemin. La confiance en soi vous aide à croire en votre potentiel et à vous concentrer sur vos objectifs, plutôt que de vous laisser submerger par les doutes et les craintes. Les personnes qui ont confiance en elles ont tendance à être plus optimistes et résilientes face aux difficultés. Elles sont plus à l'aise dans leur peau et ont une meilleure estime d'elles-mêmes, ce qui se reflète dans leurs relations avec les autres. En outre, elles sont plus susceptibles de prendre des risques et d'essayer de nouvelles choses, ce qui peut les aider à découvrir de nouveaux talents et à acquérir de nouvelles compétences. Cependant, la confiance en soi ne vient pas naturellement pour tout le monde. Certaines personnes peuvent lutter contre l'anxiété sociale, le perfectionnisme ou le manque d'estime de soi, qui peuvent toutes nuire à leur confiance en soi. Heureusement, il existe des stratégies que vous pouvez utiliser pour renforcer votre confiance en vous. Une façon de renforcer votre confiance en vous est de travailler sur votre estime de soi. Cela peut inclure la pratique de l'auto-compassion, l'identification de vos forces et de vos faiblesses et la célébration de vos réussites, même les plus petites. Une autre façon de renforcer votre confiance en vous est de vous fixer des objectifs réalisables et de travailler dur pour les atteindre. Chaque fois que vous atteignez un objectif, vous renforcez votre confiance en vous-même et vous vous sentez plus compétent.", "<p><span>La confiance en soi est une qualité essentielle pour réussir dans la vie. Elle est la clé qui ouvre les portes de l'opportunité et vous permet de surmonter les obstacles qui se dressent sur votre chemin. La confiance en soi vous aide à croire en votre potentiel et à vous concentrer sur vos objectifs, plutôt que de vous laisser submerger par les doutes et les craintes. Les personnes qui ont confiance en elles ont tendance à être plus optimistes et résilientes face aux difficultés. Elles sont plus à l'aise dans leur peau et ont une meilleure estime d'elles-mêmes, ce qui se reflète dans leurs relations avec les autres. En outre, elles sont plus susceptibles de prendre des risques et d'essayer de nouvelles choses, ce qui peut les aider à découvrir de nouveaux talents et à acquérir de nouvelles compétences. Cependant, la confiance en soi ne vient pas naturellement pour tout le monde. Certaines personnes peuvent lutter contre l'anxiété sociale, le perfectionnisme ou le manque d'estime de soi, qui peuvent toutes nuire à leur confiance en soi. Heureusement, il existe des stratégies que vous pouvez utiliser pour renforcer votre confiance en vous. Une façon de renforcer votre confiance en vous est de travailler sur votre estime de soi. Cela peut inclure la pratique de l'auto-compassion, l'identification de vos forces et de vos faiblesses et la célébration de vos réussites, même les plus petites. Une autre façon de renforcer votre confiance en vous est de vous fixer des objectifs réalisables et de travailler dur pour les atteindre. Chaque fois que vous atteignez un objectif, vous renforcez votre confiance en vous-même et vous vous sentez plus compétent. </span></p>"),
(3,1,"The cold of the early morning seemed to creep into every corner of the city, wrapping itself around the buildings and streets of Gothenburg like a persistent shadow. Detective Inspector Irene Huss pulled her coat tighter around her body, the chill in the air biting through the fabric. She had learned long ago that the Swedish winters didn't just affect the weather—they got under your skin, into your bones, and settled there.

It was a quarter past seven when the call had come in, sharp and clear: A body. High-end apartment. Norrmalm. No further details, just those two words. High-end. That was enough to stir her senses. A murder in one of the wealthiest parts of the city was never just a murder. It was a puzzle. A delicate, dangerous game of appearances, secrets, and power.

Arriving at the scene, Irene was greeted by the usual chaos—uniformed officers standing guard, a few witnesses murmuring in the background, and the inevitable flood of reporters that always seemed to materialize at the worst possible moment. But there was something different this time. The silence in the hallway felt too heavy, like the walls themselves were holding their breath.

The apartment door stood ajar, revealing a living room filled with glass, steel, and the unmistakable scent of blood. A man lay sprawled across the polished hardwood floor, his eyes wide open in shock, his mouth slightly agape as if he were still trying to make sense of the life that had just slipped away.

Irene stepped inside, her eyes scanning the room. The victim was a well-known figure, at least to those who followed the world of business and high society. But who would want him dead? And why?

She knelt down beside the body, noting the deep gash across his throat. No weapon in sight. No sign of forced entry. A clean kill, but why had it been so quiet? She couldn't shake the feeling that this wasn't just about money or revenge. There was something more beneath the surface, something hidden in plain sight.

It was time to start digging..", "<p><span>The cold of the early morning seemed to creep into every corner of the city, wrapping itself around the buildings and streets of Gothenburg like a persistent shadow. Detective Inspector Irene Huss pulled her coat tighter around her body, the chill in the air biting through the fabric. She had learned long ago that the Swedish winters didn't just affect the weather—they got under your skin, into your bones, and settled there.</span></p>

<p><span>It was a quarter past seven when the call had come in, sharp and clear: A body. High-end apartment. Norrmalm. No further details, just those two words. High-end. That was enough to stir her senses. A murder in one of the wealthiest parts of the city was never just a murder. It was a puzzle. A delicate, dangerous game of appearances, secrets, and power.</span></p>

<p><span>Arriving at the scene, Irene was greeted by the usual chaos—uniformed officers standing guard, a few witnesses murmuring in the background, and the inevitable flood of reporters that always seemed to materialize at the worst possible moment. But there was something different this time. The silence in the hallway felt too heavy, like the walls themselves were holding their breath.</span></p>

<p><span>The apartment door stood ajar, revealing a living room filled with glass, steel, and the unmistakable scent of blood. A man lay sprawled across the polished hardwood floor, his eyes wide open in shock, his mouth slightly agape as if he were still trying to make sense of the life that had just slipped away.</span></p>

<p><span>Irene stepped inside, her eyes scanning the room. The victim was a well-known figure, at least to those who followed the world of business and high society. But who would want him dead? And why?</span></p>

<p><span>She knelt down beside the body, noting the deep gash across his throat. No weapon in sight. No sign of forced entry. A clean kill, but why had it been so quiet? She couldn't shake the feeling that this wasn't just about money or revenge. There was something more beneath the surface, something hidden in plain sight.</span></p>

It was time to start digging.</span></p>"),
(4,1,"Irene stood up, her gloved fingers brushing against the edge of the body bag. The victim's face, once full of expression, now held an eerie stillness—his mouth slightly open, eyes wide as if frozen in the final moment of surprise. She didn't recognize him immediately, but his expensive suit and wristwatch told a story of wealth. Wealth often brought more than just privilege; it brought danger, envy, and enemies.

She glanced at the uniformed officer standing at the doorway. 'What do we know so far?'

'Not much, ma'am,' he replied, his breath visible in the cold air of the apartment. 'The victim is Lars Jonsson, CEO of a tech firm—uh, 'Vexor Innovations,' based here in the city. He was supposed to be meeting with investors last night, but none of them showed up. His assistant called it in after trying to reach him all morning.'

Irene nodded, her eyes still scanning the room. 'No sign of a struggle?' she asked, crouching beside a coffee table adorned with a half-drunk glass of red wine.

'None. Everything looks... neat. Too neat, actually.'

Irene raised an eyebrow. 'Too neat?'

'Yeah,' the officer said. 'Like someone didn't want to disturb the surroundings. No broken glass, no furniture out of place. The only thing that's off is... well, him.'

Irene's mind was already working, sorting through the clues like pieces of a puzzle. A businessman killed in his pristine apartment with no struggle, no obvious motive. The perfect crime, or perhaps the perfect illusion. She'd seen enough to know that things were rarely as they seemed at first glance.

She turned to the officer. 'Have the forensics team started?'

'Yes, but they'll need a few hours to complete the initial sweep.'

Irene glanced out the large window overlooking the city. The streets were already busy, people going about their day, unaware of the darkness that had unfolded in this quiet, luxurious building.

She reached into her coat pocket and pulled out her phone. Her first call was to her partner, Viktor. He'd been her colleague for years, reliable and sharp, but his impatience sometimes led him to act without thinking.

'Viktor,' she said when he picked up, 'meet me at the office in an hour. We've got a case. Lars Jonsson is dead.'

There was a pause on the other end. 'Jonsson? The tech guy?'

'Yes,' Irene said. 'Start looking into his company. Investors, business dealings. Something doesn't add up.'

'On it,' Viktor replied.

Irene hung up and turned back to the scene, her mind racing through a thousand possible scenarios. The question wasn't just who had killed Jonsson—it was why.

She stepped out of the apartment and into the cold, bracing herself for what would undoubtedly be a long day. As she walked toward the elevator, she couldn't shake the feeling that this was only the beginning of something far more complex than a simple murder.", "<p><span>Irene stood up, her gloved fingers brushing against the edge of the body bag. The victim's face, once full of expression, now held an eerie stillness—his mouth slightly open, eyes wide as if frozen in the final moment of surprise. She didn't recognize him immediately, but his expensive suit and wristwatch told a story of wealth. Wealth often brought more than just privilege; it brought danger, envy, and enemies.</span></p>

<p><span>She glanced at the uniformed officer standing at the doorway. 'What do we know so far?'</span></p>

<p><span>'Not much, ma'am,' he replied, his breath visible in the cold air of the apartment. 'The victim is Lars Jonsson, CEO of a tech firm—uh, 'Vexor Innovations,' based here in the city. He was supposed to be meeting with investors last night, but none of them showed up. His assistant called it in after trying to reach him all morning.'</span></p>

<p><span>Irene nodded, her eyes still scanning the room. 'No sign of a struggle?' she asked, crouching beside a coffee table adorned with a half-drunk glass of red wine.</span></p>

<p><span>'None. Everything looks... neat. Too neat, actually.'</span></p>

<p><span>Irene raised an eyebrow. 'Too neat?'</span></p>

<p><span>'Yeah,' the officer said. 'Like someone didn't want to disturb the surroundings. No broken glass, no furniture out of place. The only thing that's off is... well, him.'</span></p>

<p><span>Irene's mind was already working, sorting through the clues like pieces of a puzzle. A businessman killed in his pristine apartment with no struggle, no obvious motive. The perfect crime, or perhaps the perfect illusion. She'd seen enough to know that things were rarely as they seemed at first glance.</span></p>

<p><span>She turned to the officer. 'Have the forensics team started?'</span></p>

<p><span>'Yes, but they'll need a few hours to complete the initial sweep.'</span></p>

<p><span>Irene glanced out the large window overlooking the city. The streets were already busy, people going about their day, unaware of the darkness that had unfolded in this quiet, luxurious building.</span></p>

<p><span>She reached into her coat pocket and pulled out her phone. Her first call was to her partner, Viktor. He'd been her colleague for years, reliable and sharp, but his impatience sometimes led him to act without thinking.</span></p>

<p><span>'Viktor,' she said when he picked up, 'meet me at the office in an hour. We've got a case. Lars Jonsson is dead.'</span></p>

<p><span>There was a pause on the other end. 'Jonsson? The tech guy?'</span></p>

<p><span>'Yes,' Irene said. 'Start looking into his company. Investors, business dealings. Something doesn't add up.'</span></p>

<p><span>'On it,' Viktor replied.</span></p>

<p><span>Irene hung up and turned back to the scene, her mind racing through a thousand possible scenarios. The question wasn't just who had killed Jonsson—it was why.</span></p>

<p><span>She stepped out of the apartment and into the cold, bracing herself for what would undoubtedly be a long day. As she walked toward the elevator, she couldn't shake the feeling that this was only the beginning of something far more complex than a simple murder.</span></p>");

INSERT INTO offer VALUES 
(1, 'Basic', 450, 5, 1, NULL, NULL),
(2, 'Standard', 1200, 10, 1, NULL, NULL),
(3, 'Premium', 2500, 20, 1, NULL, NULL),
(4, 'Ultimate', 8000, 50, 1, NULL, NULL);