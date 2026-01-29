<span class="verified-badge badge-{{ $badgeType ?? 'verified' }}">
    <i class="fas fa-check-circle"></i>
    @if(isset($badgeType) && $badgeType === 'premium')
        <span>Premium</span>
    @elseif(isset($badgeType) && $badgeType === 'professional')
        <span>Professional</span>
    @else
        <span>Verified</span>
    @endif
</span>