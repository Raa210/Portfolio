document.addEventListener("DOMContentLoaded", () => {
    // Add splash-active to prevent scrolling
    document.body.classList.add('splash-active');

    const splashScreen = document.getElementById('splash-screen');
    const phase1 = document.getElementById('splash-phase-1');
    const phase2 = document.getElementById('splash-phase-2');
    const progressBar = document.querySelector('.progress-bar');
    const pinDots = document.querySelectorAll('.pin-dot');

    // Make sure Phase 2 is hidden initially
    phase2.classList.add('splash-hidden');

    // 1. Progress Bar Animation
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.floor(Math.random() * 10) + 5; // Random jump between 5 and 15
        if (progress > 100) progress = 100;
        
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }

        if (progress === 100) {
            clearInterval(progressInterval);
            
            // Wait a moment at 100%, then transition to Phase 2
            setTimeout(() => {
                if (phase1 && phase2) {
                    phase1.classList.add('splash-hidden');
                    phase2.classList.remove('splash-hidden');
                    
                    // 2. Start PIN typing animation after a small delay
                    setTimeout(startPinAnimation, 400);
                }
            }, 600); 
        }
    }, 150); // Update frequency

    function startPinAnimation() {
        let currentDot = 0;
        const pinInterval = setInterval(() => {
            if (currentDot < pinDots.length) {
                pinDots[currentDot].classList.add('filled');
                currentDot++;
            } else {
                clearInterval(pinInterval);
                
                // 3. Login successful, circle reveal transition
                setTimeout(() => {
                    // Start circle reveal
                    splashScreen.classList.add('circle-reveal');
                    
                    // Remove splash-active class to restore scrolling after animation
                    setTimeout(() => {
                        document.body.classList.remove('splash-active');
                        // Optional: remove splash screen from DOM
                        setTimeout(() => {
                            splashScreen.remove();
                        }, 500);
                    }, 1200); // matches the transition duration in CSS
                }, 600);
            }
        }, 400); // Speed of each PIN dot appearing
    }
});
