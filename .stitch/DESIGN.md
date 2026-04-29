# Design System: GuardYou Premium Booking
**Project ID:** 16216970727664180720

## 1. Visual Theme & Atmosphere
**Creative North Star: "The Obsidian Vault"**
This design system evokes high-stakes discretion and impenetrable luxury. It rejects "utility-first" aesthetics in favor of a bespoke concierge experience. 
The atmosphere is defined by **Tonal Depth**, **Glassmorphism**, and **Intentional Asymmetry**. We move beyond the rigid grid to create a sense of physical layering and protection.

## 2. Color Palette & Roles
The color strategy centers on the interplay between the void (Deep Navy) and the light (Gold).

| Descriptive Name | Hex Code | Functional Role |
|:---|:---|:---|
| **Obsidian Abyss** | `#0E141C` | Page Background (Base Layer) |
| **Surface Dark** | `#161C24` | Sectioning & Tonal Separation |
| **Sovereign Gold** | `#E9C176` | Primary Action, Status, & Brand Flourish |
| **Champagne Gold** | `#E0C389` | Tertiary Details & Highlights |
| **Steel Blue** | `#BAC8DC` | Secondary Accents & Hero Gradients |
| **Elevated Surface**| `#2F353E` | Cards & High-Interaction Elements |
| **Phantom White** | `#DDE3EF` | Primary Typography (On Surface) |
| **Dimmed Text** | `#C4C6CC` | Secondary Details & Metadata |

## 3. Typography Rules
**Font Family:** Inter (Swiss-inspired authority)

*   **Display:** 700 weight, -0.02em letter-spacing. Used for Hero statements.
*   **Labels:** Uppercase, 0.1em letter-spacing. Used for status chips (often in Gold).
*   **Body:** Line-height 1.6. Use Dimmed Text (#C4C6CC) for technical details to reduce noise.

## 4. Component Stylings
### Buttons (The "Gold Standard")
*   **Shape:** Sharp/Architectural (`DEFAULT` - 0.25rem). No pill shapes.
*   **Style:** Linear gradient from Sovereign Gold to Champagne Gold.
*   **Typography:** Bold, Uppercase `on_secondary` (#412D00).

### Containers & Cards
*   **The "No-Line" Rule:** Borders are strictly forbidden for sectioning. Use color shifts (e.g., `Surface Dark` on `Obsidian Abyss`) to define boundaries.
*   **Glassmorphism:** Use semi-transparent `Surface Dark` with `backdrop-filter: blur(20px)` and a 15% opacity Ghost Border (`outline_variant`).

### Security Status
*   **Indicators:** Minimalist. A Gold dot next to uppercase label text.
*   **Interactive Steps:** Use the "Ambient Glow" (diffused shadow with 40-60px blur) for floating action clusters.

## 5. Layout Principles
*   **Atmospheric Spacing:** Intense use of negative space (Spacing 16/20/24) between major sections to convey "luxury security."
*   **Asymmetry:** Overlapping glass cards on bold display type to achieve an editorial feel.
*   **Rhythm:** Vertical rhythm is defined by surface-tier changes, not lines.

## 6. Development Integration (GuardYou Logic)
*   **Rate Limiting:** Protects mission-critical routes (Booking, Chat, Payment) via `throttle:5,1` and `throttle:30,1`.
*   **Transactional Integrity:** Booking creation and Webhook processing use Pessimistic Locking (`lockForUpdate`) to prevent race conditions during high-concurrency assignments.
*   **Status Transitions:**
    *   `pending` → `paid` (via Webhook)
    *   `paid` → `active` (Agent initiates task)
    *   `active` → `completed` (Agent completes task)
