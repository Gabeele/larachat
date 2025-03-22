/**
 * Creates a plucky notification sound similar to modern messaging apps
 */
export const playNotificationSound = (): void => {
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();

    // Create pluck oscillator
    const pluckOsc = audioContext.createOscillator();
    pluckOsc.type = 'triangle';
    pluckOsc.frequency.setValueAtTime(880, audioContext.currentTime); // A5
    pluckOsc.frequency.exponentialRampToValueAtTime(440, audioContext.currentTime + 0.08); // Quick drop to A4

    // Create body oscillator for warmth
    const bodyOsc = audioContext.createOscillator();
    bodyOsc.type = 'sine';
    bodyOsc.frequency.setValueAtTime(440, audioContext.currentTime); // A4

    // Create filters
    const pluckFilter = audioContext.createBiquadFilter();
    pluckFilter.type = 'lowpass';
    pluckFilter.frequency.setValueAtTime(2500, audioContext.currentTime);
    pluckFilter.frequency.exponentialRampToValueAtTime(200, audioContext.currentTime + 0.08);
    pluckFilter.Q.setValueAtTime(2, audioContext.currentTime);

    const bodyFilter = audioContext.createBiquadFilter();
    bodyFilter.type = 'lowpass';
    bodyFilter.frequency.setValueAtTime(880, audioContext.currentTime);
    bodyFilter.Q.setValueAtTime(1, audioContext.currentTime);

    // Create compressor for punch
    const compressor = audioContext.createDynamicsCompressor();
    compressor.threshold.setValueAtTime(-12, audioContext.currentTime);
    compressor.knee.setValueAtTime(6, audioContext.currentTime);
    compressor.ratio.setValueAtTime(3, audioContext.currentTime);
    compressor.attack.setValueAtTime(0.001, audioContext.currentTime);
    compressor.release.setValueAtTime(0.1, audioContext.currentTime);

    // Create gain nodes for envelope
    const pluckGain = audioContext.createGain();
    pluckGain.gain.setValueAtTime(0.6, audioContext.currentTime);
    pluckGain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.08);

    const bodyGain = audioContext.createGain();
    bodyGain.gain.setValueAtTime(0.3, audioContext.currentTime);
    bodyGain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

    // Create master gain
    const masterGain = audioContext.createGain();
    masterGain.gain.setValueAtTime(0.7, audioContext.currentTime);

    // Connect pluck chain
    pluckOsc.connect(pluckFilter);
    pluckFilter.connect(pluckGain);
    pluckGain.connect(compressor);

    // Connect body chain
    bodyOsc.connect(bodyFilter);
    bodyFilter.connect(bodyGain);
    bodyGain.connect(compressor);

    // Final output chain
    compressor.connect(masterGain);
    masterGain.connect(audioContext.destination);

    // Play the sound
    pluckOsc.start(audioContext.currentTime);
    bodyOsc.start(audioContext.currentTime);

    pluckOsc.stop(audioContext.currentTime + 0.15);
    bodyOsc.stop(audioContext.currentTime + 0.15);

    // Clean up
    setTimeout(() => {
        audioContext.close();
    }, 200);
};

// Legacy ping sound kept for reference
export const playPingSound = (
    frequency: number = 880,
    duration: number = 100,
    volume: number = 0.1
): void => {
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();

    const oscillator = audioContext.createOscillator();
    oscillator.type = 'sine';
    oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);

    const gainNode = audioContext.createGain();
    gainNode.gain.setValueAtTime(volume, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration / 1000);

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + duration / 1000);

    setTimeout(() => {
        audioContext.close();
    }, duration + 100);
};
