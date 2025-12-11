@extends('layouts.arsha')
@section('title', 'Professionally Reliable Solution')
@section('content')
    <section id="hero" class="hero section dark-background">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                    <h1>Affordable and Reliable, Without Compromise.</h1>
                    <p>We are obsessively focused on delivering products built to a high standard, minus the overpriced cost.</p>
                    <div class="d-flex">
                        <a href="#about" class="btn-get-started">Get Started</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="https://static.silverspoon.me/system/internal/template/arsha/02222025/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about section">
        <div class="container section-title">
            <h2>About Us</h2>
        <p>Meet Silverspoon, the family project of Azhar and Salma. This creative collaboration unites Azhar's tech expertise from 2019 with Salma's artistic vision from 2020, merging code and canvas into a unique and warm experience together, known as the Silverspoon project.</p>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <p><strong>About Azhar</strong></p>
                    <p>Azhar brings a strong foundation in programming, honed since 2019. His expertise is built on a core stack of Laravel, Eloquent, jQuery and VueJS. As project demands evolved, he has also grown into managing DevSecOps for seamless deployments.</p>
                </div>
                <div class="col-lg-6">
                    <p><strong>About Salma</strong></p>
                    <p>Salma has cultivated her artistic passion into a professional illustration career since 2020. She skillfully wields tools like Clip Studio Paint, Figma and GoPaint to create her artwork. Her focus now includes sharing her creations with the world as a microstocker on platforms like Adobe Stock, Canva and Vecteezy.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="skills" class="skills section light-background">
        <div class="container section-title">
            <h2>Skill</h2>
            <p>To help you get to know us better, we're happy to give you a detailed look at our skills and experience.</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                    <h3>Azhar skill set</h3>
                    <p class="fst-italic">"My core strength lies in crafting robust backend systems, where I build the powerful engine that makes everything run. While my passion is under the hood, I'm fully capable at handling full-stack development."</p>
                    <div class="skills-content skills-animation">
                        <div class="progress">
                            <span class="skill"><span>Laravel</span><i class="val">90%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>Bootstrap</span><i class="val">80%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>jQuery</span><i class="val">80%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>VueJS</span><i class="val">70%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                    <h3>Salma skill set</h3>
                    <p class="fst-italic">"My focus is on crafting microstock assets, but my true passion is adaptability. Let me create illustrations that don't just meet your requirements, but also seamlessly fit your desired style."</p>
                    <div class="skills-content skills-animation">
                        <div class="progress">
                            <span class="skill"><span>Clip Studio Paint</span><i class="val">85%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>Figma</span><i class="val">85%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>Procreate</span><i class="val">75%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress">
                            <span class="skill"><span>Procreate Dreams</span><i class="val">75%</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="list-unstyled">
                <li><small><i>*Based on personal subjective assessment.</i></small></li>
                <li><small><i>**A more detailed skill set can be found inside the CV, if applicable.</i></small></li>
            </ul>
        </div>
    </section>

    <section id="portfolio" class="portfolio section">
        <div class="container section-title">
            <h2>Portfolio</h2>
            <p>We have several portfolios that we can present as examples of the work we have done.</p>
        </div>
        <div class="container">
            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
                <ul class="portfolio-filters isotope-filters">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-illustration">Illustration</li>
                    <li data-filter=".filter-web">Web</li>
                </ul>
                <div class="row gy-4 isotope-container">
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-illustration">
                        <img src="https://static.silverspoon.me/project/itssanadz/image/porto/stream/emote/1.webp" class="img-fluid" alt="Emotes Chibi Waka">
                        <div class="portfolio-info">
                            <h4>Emotes Chibi Waka</h4>
                            <p>Custom emoticon of Waka. Suitable as chat emotes or even avatar.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-illustration">
                        <img src="https://static.silverspoon.me/project/itssanadz/image/porto/stream/badge/1.webp" class="img-fluid" alt="Badges Love">
                        <div class="portfolio-info">
                            <h4>Badges Love</h4>
                            <p>Badge of cute love. Suitable as chat badges to differentiate the level of fans in the community.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-web">
                        <img src="https://static.silverspoon.me/project/silverspoon/image/porto/vTual.webp" class="img-fluid" alt="vTual Project">
                        <div class="portfolio-info">
                            <h4>vTual Project</h4>
                            <p>An aggregator site that tracks the activity and statistics of known streamers and/or vTubers, both from Twitch and YouTube.</p>
                            <a href="https://www.vtual.net/" target="_blank" class="details-link"><i class="bi bi-link-45deg"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-illustration">
                        <img src="https://static.silverspoon.me/project/itssanadz/image/porto/stream/badge/4.webp" class="img-fluid" alt="Badges Cute Dessert">
                        <div class="portfolio-info">
                            <h4>Badges Cute Dessert</h4>
                            <p>Badge of cute dessert. Your badge could be something different and cute at the same time.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-illustration">
                        <img src="https://static.silverspoon.me/project/itssanadz/image/porto/illust/11.webp" class="img-fluid" alt="Semi-realism">
                        <div class="portfolio-info">
                            <h4>Semi-realism</h4>
                            <p>Art styles is not always about anime-style. Sometimes something like semi-realism does exist!</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-illustration">
                        <img src="https://static.silverspoon.me/project/itssanadz/image/porto/stream/emote/4.webp" class="img-fluid" alt="Emotes Cute Red Panda">
                        <div class="portfolio-info">
                            <h4>Emotes Cute Red Panda</h4>
                            <p>Custom emoticon of cute red panda. Aren't they just too adorable?</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services section light-background">
        <div class="container section-title">
            <h2>Services</h2>
            <p>You've found the heart of our family project. Here's a little about who we are and the services we provide.</p>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-file-break-fill"></i></div>
                        <h4><span class="stretched-link">Landing Page</span></h4>
                        <p>A landing page to share information about you and what you can offer to the world.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-window-split"></i></div>
                        <h4><span class="stretched-link">Simple Apps</span></h4>
                        <p>A simple, yet easy-to-use app that makes simple things, well... simpler.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-window-stack"></i></div>
                        <h4><span class="stretched-link">Complicated Apps</span></h4>
                        <p>Or perhaps you need a more sophisticated system, like a pre-made ERP.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-brush"></i></div>
                        <h4><span class="stretched-link">Illustration</span></h4>
                        <p>Bringing your ideas into canvas, crafted in a wide range of artistic styles.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-emoji-smile"></i></div>
                        <h4><span class="stretched-link">Emoticon</span></h4>
                        <p>Enlighten your community conversations and reactions with unique, custom emoticons.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 d-flex">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-person-badge"></i></div>
                        <h4><span class="stretched-link">Badge</span></h4>
                        <p>Elevate your community engagement with a gamified prestige badge system that members will love.</p>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <div class="service-item position-relative">
                        <div class="icon"><i class="bi bi-eye"></i></div>
                        <h4><span class="stretched-link">Care to Hire</span></h4>
                        <p>We understand that some custom-made project require more time, focus and care compared to pre-made solutions. We're committed to giving your vision the detailed attention and dedication it deserves, so that it can turns into a reality.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="why-us" class="section why-us">
        <div class="container-fluid">
            <div class="row gy-4">
                <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">
                    <div class="content px-xl-5">
                        <h3>Have a question? <strong>We've got you covered!</strong></h3>
                        <p><i>You heard it right! You probably have a few questions, so we've gathered the answers to the most common ones right here.</i></p>
                    </div>
                    <div class="accordion" id="accordionFAQ">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What is Silverspoon?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    <p>Silverspoon is a heartfelt family project, lovingly built and run by Azhar and Salma. We offer combined services focused on our core passions: programming and illustration. While we began our professional journey in 2019, our love and exposure for these crafts started long before, giving our work a depth of genuine passion.</p>
                                    <hr />
                                    <p><small><i>Trivia: The <a href="https://who.is/whois/silverspoon.me" target="_blank">Silverspoon domain</a> has been registered since 2020, but has only been used as a landing page since late November 2025.</i></small></p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What is Silverspoon history
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    <p>At first, Silverspoon was the code name for one of my programming projects. But as time passed, without my knowing I already have developed several projects.</p>
                                    <p>I love the terms "Silverspoon" but cannot assign it to the original project it belonged to. With that in mind, then Silverspoon became the main project name, while the original project was renamed/rebranded as "vTual."</p>
                                    <p>After that, the projects I develop are usually published on the internet and can be used publicly, though the code base itself is mostly closed source. But still, it is possible that there will be several open source projects in the future.</p>
                                    <p>The developed project primarily consist of everyday applications based on a SaaS (Software as a Service) model. I rarely create an application that is specificly tied to one person or company, that's all simply because I really like the SaaS business model.</p>
                                    <p>My short-term goal for those project is to serve it as a portfolio of my skill set. Meanwhile, the long-term goal is to make these projects to be able to run professionally as matured, product-driven business.</p>
                                    <p>However, in late 2025, I felt the need for a public landing page to showcase my wife's and my portfolio as a new business model expansion, so that we can start to be open to working on projects for client needs. Thus, Silverspoon's primary domain was used for the landing page.</p>
                                    <p>So hey everyone, we're open for the public!</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What is Silverspoon terminology?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    <p>As a brand name, "Silverspoon" certainly has its own meaning and reasons for being chosen as a name. Citing Wikipedia, Silverspoon can mean:</p>
                                    <blockquote class="blockquote">
                                        <p><i>As an adjective, "silver spoon" describes someone who has a prosperous background or is of a well-to-do family environment.</i></p>
                                    </blockquote>
                                    <p>But of course the form of the citation is still quite abstract and does not explain anything clearly. Therefore I still have to explain the reasons for choosing "Silverspoon" as a brand name.</p>
                                    <p>The reason is as simple as; I want everyone who has a relationship with Silverspoon to have receive goodness and prosperity. Be it for the owner, client, customer, user or associate. Just like the silver spoon out there. So that we all can one day, be the silver spoon itself.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What is the tech stack?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    <p>My technical foundation is built on a robust and reliable internal stack that has served me well since the beginning: Laravel powers the backend, authenticated with JWT, while the frontend is crafted with Blade and jQuery/VueJS. This is all supported by PostgreSQL, elegantly managed via the Eloquent ORM and deployed on an Ubuntu server with nginx and/or Apache web server.</p>
                                    <p>To ensure performance and security, I integrate with powerful external services like S3-compatible storage, GitHub/Gitlab for version control and acme for certificate management, all protected by Cloudflare Zero Trust.</p>
                                    <p>I'm also continuously growing my toolkit. I'm currently exploring and adapting to new technologies like FrankenPHP, HAProxy, Patroni, Redis, RabbitMQ, containerization (Docker/Kubernetes) and automation (n8n/Zapier) to solve more complex problems while scaling. Throughout all my projects, I employ the repository pattern to ensure a clean, maintainable and scalable architecture.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    What is the rate card?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    <p>We currently don't have a public rate card available as we're still in the preparation stage. Once the rate card is ready to be published, it will be posted on the relevant information page.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-1 order-lg-2 why-us-img">
                    <img src="https://static.silverspoon.me/system/internal/template/arsha/02222025/img/why-us.png" class="img-fluid" alt="Why Us">
                </div>
            </div>
        </div>
    </section>

    <section id="team" class="team section light-background   cxcdsesr ">
        <div class="container section-title">
            <h2>Team</h2>
            <p>We're the faces and hearts of the Silverspoon project and we are looking forward to getting in touch with you.</p>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="team-member d-flex align-items-start">
                        <div class="pic">
                            <img src="https://static.silverspoon.me/system/internal/image/misc/wakava/default/Pasfoto-Waka-1.webp" class="img-fluid" alt="Azhar Fahrurazi">
                        </div>
                        <div class="member-info">
                            <h4>Azhar Fahrurazi</h4>
                            <span>Programmer, the Husband</span>
                            <p>A backend specialist at heart, with the proven flexibility to seamlessly handle full-stack development when needed.</p>
                            <div class="social">
                                <a href="https://linkedin.com/in/azhar-fahrurazi-raka-praja" target="_blank"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="team-member d-flex align-items-start">
                        <div class="pic">
                            <img src="https://static.silverspoon.me/system/internal/template/arsha/02222025/img/person/person-f-8.webp" class="img-fluid" alt="">
                        </div>
                        <div class="member-info">
                            <h4>Salma Nadzirah</h4>
                            <span>Illustrator, the Wife</span>
                            <p>The dynamic world of microstock has honed the experience to draw in countless styles, making adaptable and versatile.</p>
                            <div class="social">
                                <a href="https://www.instagram.com/itssanadz" target="_blank"><i class="bi bi-instagram"></i></a>
                                <a href="https://www.tiktok.com/@itssanadz" target="_blank"><i class="bi bi-tiktok"></i></a>
                                <a href="https://stock.adobe.com/uk/contributor/211699431/Salma" title="Adobe Stock" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>
                                <a href="https://www.canva.com/p/its-sanadz" title="Canva" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>
                                <a href="https://www.vecteezy.com/members/113449829645431624840" title="Vecteezy" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="call-to-action" class="call-to-action section dark-background">
        <img src="https://static.silverspoon.me/system/internal/template/arsha/02222025/img/bg/bg-8.webp" alt="CTA">
        <div class="container">
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <div class="col-xl-9 text-center text-xl-start">
                    <h3>Ready to start?</h3>
                    <p>If you think we have something to offer you, don't hesitate to contact us to talk about the possibility of cooperation. We're open to any reasonable inquiry.</p>
                </div>
                <div class="col-xl-3 cta-btn-container text-center">
                    <a class="cta-btn align-middle" href="{{ route('fe.cta.message') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection