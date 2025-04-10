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

#
#
# import sys
# import json
# from difflib import SequenceMatcher
# from sentence_transformers import SentenceTransformer
# import numpy as np
#
# def calculate_semantic_similarity(str1, str2):
#     try:
#         # Load the lightweight model (only once for better performance)
#         model = SentenceTransformer('all-MiniLM-L6-v2')
#
#         # Encode both sentences
#         embeddings = model.encode([str1, str2])
#
#         # Calculate cosine similarity
#         similarity = np.dot(embeddings[0], embeddings[1]) / (
#             np.linalg.norm(embeddings[0]) * np.linalg.norm(embeddings[1]))
#
#         return round(float(similarity) * 100, 1)
#     except Exception as e:
#         print(f"Semantic similarity error: {str(e)}", file=sys.stderr)
#         return 0.0
#
# def calculate_string_similarity(str1, str2):
#     try:
#         return round(SequenceMatcher(None, str1.lower(), str2.lower()).ratio() * 100, 1)
#     except Exception as e:
#         print(f"String similarity error: {str(e)}", file=sys.stderr)
#         return 0.0
#
# def main():
#     if len(sys.argv) != 3:
#         print(json.dumps({"error": "Please provide exactly two strings to compare"}))
#         sys.exit(1)
#
#     str1 = sys.argv[1]
#     str2 = sys.argv[2]
#
#     # Calculate both similarity measures
#     string_sim = calculate_string_similarity(str1, str2)
#     semantic_sim = calculate_semantic_similarity(str1, str2)
#
#     # Calculate weighted combined score
#     combined_sim = (string_sim * 0.4) + (semantic_sim * 0.6)
#
#     result = {
#         "string_similarity": string_sim,
#         "semantic_similarity": semantic_sim,
#         "combined_similarity": round(combined_sim, 1),
#         "is_similar": combined_sim > 70  # You can adjust this threshold
#     }
#
#     print(json.dumps(result, indent=2))
#
# if __name__ == "__main__":
#     main()
