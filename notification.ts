/**
 * Plays a modern notification sound using Web Audio API
 * Creates a two-tone notification with slight frequency modulation
 */
export const playNotificationSound = (): void => {
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();
    
    // Create main oscillators for two-tone effect
    const primaryOsc = audioContext.createOscillator();
    const secondaryOsc = audioContext.createOscillator();
    
    // Create gain nodes
    const primaryGain = audioContext.createGain();
    const secondaryGain = audioContext.createGain();
    const masterGain = audioContext.createGain();
    
    // Configure oscillators
    primaryOsc.type = 'sine';
    secondaryOsc.type = 'sine';
    
    // Set frequencies (C6 and E6 for a pleasant chord)
    primaryOsc.frequency.setValueAtTime(1046.50, audioContext.currentTime); // C6
    secondaryOsc.frequency.setValueAtTime(1318.51, audioContext.currentTime); // E6
    
    // Add slight frequency modulation for interest
    primaryOsc.frequency.setValueAtTime(1046.50, audioContext.currentTime);
    primaryOsc.frequency.exponentialRampToValueAtTime(1096.50, audioContext.currentTime + 0.1);
    
    // Set initial gains
    primaryGain.gain.setValueAtTime(0.3, audioContext.currentTime);
    secondaryGain.gain.setValueAtTime(0.2, audioContext.currentTime);
    masterGain.gain.setValueAtTime(0.15, audioContext.currentTime);
    
    // Create nice fade out envelope
    primaryGain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.15);
    secondaryGain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
    
    // Connect the audio graph
    primaryOsc.connect(primaryGain);
    secondaryOsc.connect(secondaryGain);
    primaryGain.connect(masterGain);
    secondaryGain.connect(masterGain);
    masterGain.connect(audioContext.destination);
    
    // Play the sound
    primaryOsc.start(audioContext.currentTime);
    secondaryOsc.start(audioContext.currentTime + 0.02); // Slight delay for second tone
    
    primaryOsc.stop(audioContext.currentTime + 0.15);
    secondaryOsc.stop(audioContext.currentTime + 0.2);
    
    // Clean up
    setTimeout(() => {
        audioContext.close();
    }, 300);
};

// Legacy function maintained for backward compatibility
export const playPingSound = (
    frequency: number = 880,
    duration: number = 100,
    volume: number = 0.1
): void => {
    // Create audio context
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();
    
    // Create oscillator
    const oscillator = audioContext.createOscillator();
    oscillator.type = 'sine';
    oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
    
    // Create gain node for volume control and fade out
    const gainNode = audioContext.createGain();
    gainNode.gain.setValueAtTime(volume, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration / 1000);
    
    // Connect nodes
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    // Start and stop the sound
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + duration / 1000);
    
    // Clean up
    setTimeout(() => {
        audioContext.close();
    }, duration + 100);
}; 