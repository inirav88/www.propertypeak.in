@php
    Theme::layout('full-width');
    Theme::set('pageTitle', 'TEST - Rent Agreement');
@endphp

<div style="background: #f0f0f0; padding: 20px;">
    <div class="container">
        <h1 style="color: red; font-size: 32px; font-weight: bold;">TEST - IF YOU SEE RED TEXT, STYLING WORKS</h1>
        
        <div style="background: #db1d23; color: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>This is a RED header box</h3>
        </div>
        
        <div style="background: #28a745; color: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>This is a GREEN box</h3>
        </div>
        
        <div style="background: #17a2b8; color: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>This is a BLUE box</h3>
        </div>
        
        <div style="border: 5px solid #db1d23; padding: 20px; margin: 20px 0; background: white;">
            <h3>This box has a THICK RED BORDER</h3>
        </div>
        
        <div class="row">
            <div class="col-lg-6" style="background: #ffeeee; padding: 20px;">
                <p>LEFT COLUMN (col-lg-6)</p>
            </div>
            <div class="col-lg-6" style="background: #eeffee; padding: 20px;">
                <p>RIGHT COLUMN (col-lg-6)</p>
            </div>
        </div>
    </div>
</div>
