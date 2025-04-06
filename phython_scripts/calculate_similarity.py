import sys
import json
from difflib import SequenceMatcher

def calculate_similarity(str1, str2):
    # Normalize strings
    str1 = str1.lower().strip()
    str2 = str2.lower().strip()

    # Calculate similarity ratio
    similarity = SequenceMatcher(None, str1, str2).ratio()

    # Return as percentage
    return round(similarity * 100, 1)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print(json.dumps({"error": "Please provide two strings to compare."}))
    else:
        str1 = sys.argv[1]
        str2 = sys.argv[2]
        result = {"similarity": calculate_similarity(str1, str2)}
        print(json.dumps(result))
