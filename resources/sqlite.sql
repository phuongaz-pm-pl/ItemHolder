-- # !sqlite
-- #{ holders
-- #     { init
CREATE TABLE IF NOT EXISTS holders (
    prefix TEXT NOT NULL,
    item_data TEXT NOT NULL
)
-- #     }
-- #     { select
-- #         :prefix string
SELECT item_data FROM holders WHERE prefix = :prefix
-- #     }
-- #     { insert
-- #         :prefix string
-- #         :item_data string
INSERT INTO holders (prefix, item_data) VALUES (:prefix, :item_data)
-- #     }
-- #     { delete
-- #         :prefix string
DELETE FROM holders WHERE prefix = :prefix
-- #     }
-- #}
